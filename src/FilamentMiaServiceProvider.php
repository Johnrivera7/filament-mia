<?php

namespace JohnRivera7\FilamentMia;

use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use JohnRivera7\FilamentMia\Http\Controllers\RenderPage;
use JohnRivera7\FilamentMia\Http\Controllers\SwitchLocale;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;
use JohnRivera7\FilamentMia\PageBuilder\PageContent;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JohnRivera7\FilamentMia\Settings\FileSettingsRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMiaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-mia';

    public static string $viewNamespace = 'filament-mia';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews(static::$viewNamespace)
            /*
             * Published, never loaded. The page builder is off by default, and
             * a theme that added a table to an application that never asked
             * for one would be a theme with a side effect nobody chose. Run
             * `vendor:publish --tag=filament-mia-migrations` after switching
             * the feature on.
             */
            ->hasMigration('create_mia_pages_table');
    }

    public function packageRegistered(): void
    {
        /*
         * Bound as a singleton so the memoised reads inside the default
         * repository are shared: the theme reads the settings once per panel
         * boot, and the customiser page reads them again on the same request.
         *
         * Swap this binding to store the settings anywhere else — a database
         * table, a shared cache, or per user rather than per panel. Nothing in
         * the theme depends on the default implementation.
         */
        $this->app->singleton(SettingsRepository::class, fn (Application $app): FileSettingsRepository => new FileSettingsRepository(
            files: $app->make(Filesystem::class),
            directory: $app->storagePath('app/filament-mia'),
        ));

        /*
         * A singleton for the same reason: the page builder's cache has to be
         * forgotten from a model event, and a fresh instance resolved there
         * would clear the store but leave the array the current request is
         * rendering from untouched.
         */
        $this->app->singleton(PageContent::class);
    }

    public function packageBooted(): void
    {
        /*
         * Registered here rather than inside the plugin's `register()` because
         * `php artisan filament:assets` — the command that copies the
         * stylesheet into `public/` — runs in the console, where no panel has
         * been built yet. An asset registered only from a plugin is invisible
         * to it, and the theme would never be published.
         *
         * A `Theme` asset, not a `Css` asset: Filament emits plain stylesheets
         * *before* the active theme's own `<link>`, so a `Css` asset would lose
         * the cascade to the very stylesheet it is meant to replace.
         */
        /*
         * The second argument is the Composer package name, which is what
         * Filament turns into the directory it publishes into and the URL it
         * emits — `public/css/johnrivera7/filament-mia-theme/mia.css`. It
         * tracks the package name rather than `static::$name`, which names the
         * theme's own resources instead: the config file, the view and
         * translation namespaces and the publish tags all stay `filament-mia`,
         * so an application's published overrides survive the rename.
         */
        FilamentAsset::register([
            Theme::make('mia', __DIR__ . '/../resources/dist/mia.css'),
        ], 'johnrivera7/filament-mia-theme');

        $this->registerLocaleRoute();
        $this->registerPageRoutes();
        $this->registerErrorPages();

        /*
         * A copy of the four error views in the place Laravel looks first, for
         * an application that wants to edit the wording or the markup rather
         * than take them as they come. Published to `resources/views/errors`,
         * which is the first hint path in the `errors` namespace, so the copies
         * win over the originals without any further configuration.
         *
         * Separate from the `filament-mia-views` tag that `hasViews()`
         * registers: that one publishes into `resources/views/vendor`, which is
         * where a namespaced override belongs and is not somewhere Laravel
         * resolves `errors::404` from.
         *
         * The published copies still include the layout from the package, so
         * editing the copy does not freeze the shell around it.
         */
        $this->publishes([
            __DIR__ . '/../resources/views/http/errors' => resource_path('views/errors'),
        ], 'filament-mia-errors');
    }

    /**
     * The endpoint behind the language switcher.
     *
     * Registered unconditionally, because panels are built lazily and this
     * runs before any of them exists. It is inert on its own: the controller
     * only accepts a panel that runs the theme *and* a language that panel
     * offers, so a panel with the switcher off has nothing to reach.
     *
     * `web` middleware for the session and the cookie jar, nothing more. No
     * authentication, because the sign-in screen is exactly where a visitor
     * most needs to change the language.
     */
    protected function registerLocaleRoute(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware('web')
            ->get('filament-mia/locale/{panel}/{locale}', SwitchLocale::class)
            ->name('filament-mia.locale');
    }

    /**
     * The two addresses the page builder needs, and neither before it is asked
     * for.
     *
     * Registered from an `booted` callback rather than here, and that is the
     * point of the method. Panels are built during the boot phase, so at this
     * moment no panel exists yet and there is no way to know whether any of
     * them switched the builder on. By the time the callback runs, every
     * provider has booted: the panels are built, the plugin can be asked, and
     * — the part that matters — the application's own routes are already
     * registered.
     *
     * That ordering is deliberate. Laravel matches the first route that
     * answers a path, so an application that defines its own `/` keeps it and
     * the theme's page is simply never reached. A theme taking over the site's
     * front door by being switched on would be indefensible; declining to
     * register a second route for an address somebody already claimed is not.
     */
    protected function registerPageRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $this->app->booted(function (): void {
            $panel = PageBuilderPanel::resolve();

            if ($panel === null) {
                return;
            }

            $plugin = $panel->getPlugin('mia-theme');

            if (! $plugin instanceof MiaTheme) {
                return;
            }

            $path = $plugin->getPageBuilderPath();

            Route::middleware('web')->group(function () use ($path): void {
                /*
                 * The draft, for whoever may open the builder. Its own address
                 * rather than a query string on the public one, so a published
                 * page can be cached at the edge without a parameter that
                 * would bypass it.
                 */
                Route::get('filament-mia/page-preview', [RenderPage::class, 'preview'])
                    ->name('filament-mia.page.preview');

                if (! $this->pathIsTaken($path)) {
                    Route::get($path, RenderPage::class)->name('filament-mia.page');
                }
            });
        });
    }

    /**
     * Whether something already answers a GET at this path.
     *
     * Compared against the router's own collection rather than guessed, so the
     * check is about what the application actually registered.
     */
    protected function pathIsTaken(string $path): bool
    {
        $path = ltrim($path, '/');
        $path = $path === '' ? '/' : $path;

        foreach (Route::getRoutes()->getRoutes() as $route) {
            if ($route->uri() === $path && in_array('GET', $route->methods(), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Offer the theme's error pages to Laravel's `errors` view namespace.
     *
     * Off unless `filament-mia.error_pages` says otherwise. Error views are
     * application-wide, not panel-scoped: every 404 in the application would
     * start arriving in the theme, including the ones from routes that have
     * nothing to do with a panel. That is a decision for the application to
     * make, not for a theme to make on its behalf by being installed.
     *
     * The mechanism is `view.paths` rather than `View::addNamespace('errors')`,
     * and it has to be. Laravel's exception handler calls
     * `RegisterErrorViewPaths` immediately before it renders, and that helper
     * *replaces* the whole `errors` namespace with `config('view.paths')`
     * mapped through `{path}/errors`, plus the framework's own views last.
     * Anything registered against the namespace directly is discarded at that
     * moment; a path added here is picked up by it instead.
     *
     * Appended, never prepended. The application's own `view.paths` stay in
     * front, so `resources/views/errors/404.blade.php` — whether hand-written
     * or published from Laravel — still wins. The theme only ever answers for
     * a status nobody else has claimed, and the four it answers for are the
     * only ones it ships.
     */
    protected function registerErrorPages(): void
    {
        if (! config('filament-mia.error_pages', false)) {
            return;
        }

        $paths = config('view.paths', []);
        $path = realpath(__DIR__ . '/../resources/views/http');

        if ($path === false || in_array($path, $paths, true)) {
            return;
        }

        config()->set('view.paths', [...$paths, $path]);
    }
}
