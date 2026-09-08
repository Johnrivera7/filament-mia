<?php

namespace JohnRivera7\FilamentMia;

use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use JohnRivera7\FilamentMia\Http\Controllers\SwitchLocale;
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
            ->hasViews(static::$viewNamespace);
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
        FilamentAsset::register([
            Theme::make('mia', __DIR__ . '/../resources/dist/mia.css'),
        ], 'johnrivera7/filament-mia');

        $this->registerLocaleRoute();
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
}
