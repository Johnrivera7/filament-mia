<?php

namespace JohnRivera7\FilamentMia;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\FontProviders\BunnyFontProvider;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\LoginLayout;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\Http\Middleware\ApplyLocale;
use JohnRivera7\FilamentMia\Pages\ThemeCustomizer;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JohnRivera7\FilamentMia\Settings\ThemeSettings;
use JohnRivera7\FilamentMia\Support\ColorValidator;
use JohnRivera7\FilamentMia\Support\LocaleLibrary;
use JohnRivera7\FilamentMia\Support\Palette;
use JohnRivera7\FilamentMia\Support\TokenSheet;
use UnitEnum;

/**
 * Mía — a warm editorial theme for Filament.
 *
 * The stylesheet ships pre-compiled, so nothing here generates Tailwind
 * classes. Configuration is applied in two ways instead:
 *
 *  - Colours go through Filament's own colour manager (`Panel::colors()`),
 *    which emits every shade as an OKLCH custom property on each request. The
 *    stylesheet only ever references those tokens, never literal values.
 *  - Everything else (corner radii, density, motion, elevation, type) is
 *    injected as custom properties through the `STYLES_AFTER` render hook,
 *    which Filament emits after the theme's own `<link>` and therefore wins
 *    the cascade without needing `!important`.
 *
 * Both are scoped to `.fi-panel-{id}` so several panels can run the theme with
 * different settings in the same application.
 */
class MiaTheme implements Plugin
{
    protected string $accentColor;

    protected string $secondaryColor;

    protected ?string $neutralColor = null;

    protected string $dangerColor;

    protected string $infoColor;

    protected string $successColor;

    protected string $warningColor;

    protected string $sansFont;

    protected string $serifFont;

    protected ?string $monoFont = null;

    protected bool $serifHeadings;

    protected Roundness $roundness;

    protected Density $density;

    protected bool $darkMode;

    protected bool $motion;

    protected float $elevation;

    protected string $sidebarWidth;

    protected LoginLayout $loginLayout;

    protected ?string $loginTagline;

    /** @var array<string> */
    protected array $viteStylesheets = [];

    protected ?string $viteBuildDirectory = null;

    protected bool $customizer = false;

    /** @var (Closure(): bool)|null */
    protected ?Closure $customizerAuthorization = null;

    protected string|UnitEnum|null $customizerNavigationGroup = null;

    protected ?int $customizerNavigationSort = null;

    protected string|BackedEnum|null $customizerNavigationIcon = null;

    /** @var array<string, string> */
    protected array $locales = [];

    public function __construct()
    {
        $config = config('filament-mia', []);

        $this->accentColor = ColorValidator::validate('accentColor', $config['colors']['accent'] ?? '#D4AF7A');
        $this->secondaryColor = ColorValidator::validate('secondaryColor', $config['colors']['secondary'] ?? '#E8C4C0');
        $this->dangerColor = ColorValidator::validate('dangerColor', $config['colors']['danger'] ?? '#C1614F');
        $this->infoColor = ColorValidator::validate('infoColor', $config['colors']['info'] ?? '#8B9FB0');
        $this->successColor = ColorValidator::validate('successColor', $config['colors']['success'] ?? '#8A9A6B');
        $this->warningColor = ColorValidator::validate('warningColor', $config['colors']['warning'] ?? '#D9A441');

        if (filled($neutral = $config['colors']['neutral'] ?? null)) {
            $this->neutralColor = ColorValidator::validate('neutralColor', $neutral);
        }

        $this->sansFont = ColorValidator::validateFontFamily('font', $config['fonts']['sans'] ?? 'Jost');
        $this->serifFont = ColorValidator::validateFontFamily('font', $config['fonts']['serif'] ?? 'Cormorant Garamond');

        if (filled($mono = $config['fonts']['mono'] ?? null)) {
            $this->monoFont = ColorValidator::validateFontFamily('monoFont', $mono);
        }

        $this->serifHeadings = (bool) ($config['typography']['serif_headings'] ?? true);
        $this->roundness = Roundness::fromValue($config['roundness'] ?? Roundness::Soft);
        $this->density = Density::fromValue($config['density'] ?? Density::Comfortable);
        $this->darkMode = (bool) ($config['dark_mode'] ?? true);
        $this->motion = (bool) ($config['motion'] ?? true);
        $this->elevation = (float) ($config['elevation'] ?? 1.0);
        $this->sidebarWidth = $config['sidebar_width'] ?? '17rem';
        $this->loginLayout = LoginLayout::fromValue($config['login']['layout'] ?? LoginLayout::Card);
        $this->loginTagline = $config['login']['tagline'] ?? null;
        $this->viteStylesheets = (array) ($config['vite_stylesheets'] ?? []);
        $this->viteBuildDirectory = $config['vite_build_directory'] ?? null;

        $this->customizer = (bool) ($config['customizer']['enabled'] ?? false);
        $this->customizerNavigationGroup = $config['customizer']['navigation_group'] ?? null;
        $this->customizerNavigationSort = $config['customizer']['navigation_sort'] ?? null;

        $this->locales = LocaleLibrary::normalise((array) ($config['locales'] ?? []));
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'mia-theme';
    }

    public function register(Panel $panel): void
    {
        $this->applyStoredSettings($panel->getId());

        if ($this->customizer) {
            $panel->pages([ThemeCustomizer::class]);
        }

        if ($this->locales !== []) {
            $panel
                ->middleware([ApplyLocale::class], isPersistent: true)
                // Registered flat rather than as their own group: a group
                // would switch Filament to its multi-group menu layout and
                // rearrange items the application registered itself.
                ->userMenuItems($this->localeMenuItems($panel->getId()));
        }

        $panel
            ->theme('mia')
            ->colors([
                'primary' => Palette::from($this->accentColor),
                'secondary' => Palette::from($this->secondaryColor),
                'gray' => $this->neutralColor === null
                    ? Palette::neutral()
                    : Palette::from($this->neutralColor),
                'danger' => Palette::from($this->dangerColor),
                'info' => Palette::from($this->infoColor),
                'success' => Palette::from($this->successColor),
                'warning' => Palette::from($this->warningColor),
            ])
            ->font($this->sansFont, provider: BunnyFontProvider::class)
            ->serifFont($this->serifFont, provider: BunnyFontProvider::class)
            // Filament's 20rem sidebar is one of its most recognisable traits,
            // and it crowds the content column on smaller laptops.
            ->sidebarWidth($this->sidebarWidth)
            ->darkMode($this->darkMode);

        if ($this->monoFont !== null) {
            $panel->monoFont($this->monoFont, provider: BunnyFontProvider::class);
        }
    }

    public function boot(Panel $panel): void
    {
        $css = (new TokenSheet(
            panelId: $panel->getId(),
            roundness: $this->roundness,
            density: $this->density,
            serifHeadings: $this->serifHeadings,
            motion: $this->motion,
            elevation: $this->elevation,
        ))->render();

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): HtmlString => new HtmlString($css),
        );

        $loginLayout = $this->loginLayout;
        $loginTagline = $this->loginTagline;

        /*
         * The marker that selects a sign-in composition, and the brand stage
         * for the two compositions that use one.
         *
         * Injected rather than published as an overridden Blade view: the
         * simple layout is one of the files most likely to change between
         * Filament releases, and a published copy of it would silently stop
         * tracking upstream.
         */
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIMPLE_LAYOUT_START,
            fn (): View => view('filament-mia::login-stage', [
                'layout' => $loginLayout,
                'tagline' => $loginTagline,
            ]),
        );

        if ($this->viteStylesheets !== []) {
            $stylesheets = $this->viteStylesheets;
            $buildDirectory = $this->viteBuildDirectory;

            FilamentView::registerRenderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): Htmlable => app(Vite::class)($stylesheets, $buildDirectory),
            );
        }
    }

    /**
     * Load one or more of the application's own Vite stylesheets after the
     * theme.
     *
     * A pre-compiled theme can only contain the utility classes that Filament
     * itself uses; it cannot know about classes in the *application's* Blade
     * views, because those files do not exist when the theme is built. An
     * application that writes Tailwind utilities in its own views therefore
     * needs to compile them itself, and this is where to hand them over.
     *
     * `Panel::viteTheme()` cannot be used for it, since Filament gives that
     * unconditional precedence over `theme()` and would replace this theme
     * entirely. Emitting the stylesheet through the render hook instead loads
     * it alongside the theme rather than instead of it.
     *
     * @param  string|array<string>  $paths
     */
    public function viteStylesheets(string|array $paths, ?string $buildDirectory = null): static
    {
        $this->viteStylesheets = (array) $paths;
        $this->viteBuildDirectory = $buildDirectory;

        return $this;
    }

    /**
     * The accent that carries buttons, links, focus rings and active states.
     */
    public function accentColor(string $color): static
    {
        $this->accentColor = ColorValidator::validate('accentColor', $color);

        return $this;
    }

    /**
     * A supporting colour, available to components as `->color('secondary')`.
     */
    public function secondaryColor(string $color): static
    {
        $this->secondaryColor = ColorValidator::validate('secondaryColor', $color);

        return $this;
    }

    /**
     * Replace the warm neutral that page backgrounds, surfaces, borders and
     * body copy are built from. Leave unset to keep the curated ramp.
     */
    public function neutralColor(?string $color): static
    {
        $this->neutralColor = $color === null
            ? null
            : ColorValidator::validate('neutralColor', $color);

        return $this;
    }

    /**
     * @param  string|null  $serif  Family used for headings and brand type.
     */
    public function font(string $sans, ?string $serif = null): static
    {
        $this->sansFont = ColorValidator::validateFontFamily('font', $sans);

        if ($serif !== null) {
            $this->serifFont = ColorValidator::validateFontFamily('font', $serif);
        }

        return $this;
    }

    public function monoFont(?string $family): static
    {
        $this->monoFont = $family === null
            ? null
            : ColorValidator::validateFontFamily('monoFont', $family);

        return $this;
    }

    /**
     * Set headings in the serif family, or keep the whole interface in the
     * sans family for a more neutral look.
     */
    public function serifHeadings(bool $condition = true): static
    {
        $this->serifHeadings = $condition;

        return $this;
    }

    public function roundness(Roundness|string $roundness): static
    {
        $this->roundness = Roundness::fromValue($roundness);

        return $this;
    }

    public function density(Density|string $density): static
    {
        $this->density = Density::fromValue($density);

        return $this;
    }

    public function darkMode(bool $condition = true): static
    {
        $this->darkMode = $condition;

        return $this;
    }

    /**
     * Any CSS length. Defaults to `17rem`, narrower than Filament's `20rem`.
     */
    public function sidebarWidth(string $width): static
    {
        $this->sidebarWidth = $width;

        return $this;
    }

    /**
     * Choose how the screens shown before sign-in are composed.
     *
     * Five compositions are available, and they differ in staging rather than
     * in identity: where the form sits and what occupies the rest of the
     * viewport changes, the palette and the type pairing do not. The setting
     * covers the whole authentication flow, so a panel does not change shape
     * between signing in and answering a multi-factor challenge.
     *
     *     ->loginLayout('split')
     *     ->loginLayout(LoginLayout::Split)
     *
     * Accepted values: card | split | bleed | editorial | portal.
     * Defaults to `card`.
     */
    public function loginLayout(LoginLayout|string $layout): static
    {
        $this->loginLayout = LoginLayout::fromValue($layout);

        return $this;
    }

    /**
     * A line of copy for the brand stage.
     *
     * Only the `split` and `editorial` compositions have somewhere to put it;
     * the others ignore it. Trimmed to a single line and capped, because it
     * shares its space with type set at display size.
     */
    public function loginTagline(?string $tagline): static
    {
        $this->loginTagline = $tagline;

        return $this;
    }

    /**
     * Entry animations and hover micro-interactions. Independent of
     * `prefers-reduced-motion`, which is always honoured.
     */
    public function motion(bool $condition = true): static
    {
        $this->motion = $condition;

        return $this;
    }

    /**
     * Scale the shadow system, from `0` (completely flat) upwards. Shadows are
     * tinted with the neutral rather than pure black at any value.
     */
    public function elevation(float $scale): static
    {
        $this->elevation = max(0.0, $scale);

        return $this;
    }

    /**
     * Add the appearance page to the panel, where the theme can be edited from
     * the interface and the result saved.
     *
     * Off by default. The page rewrites how the panel looks for everyone who
     * uses it, so it should be an explicit decision rather than something that
     * appears in the navigation the moment the package is installed.
     *
     * Restrict who reaches it with `customizerAuthorization()`.
     */
    public function customizer(bool $condition = true): static
    {
        $this->customizer = $condition;

        return $this;
    }

    /**
     * Decide who may open the appearance page.
     *
     * Without this, anyone who can reach the panel can open it. The callback
     * runs on every navigation build, so keep it cheap.
     *
     *     ->customizerAuthorization(fn (): bool => auth()->user()?->isAdmin())
     *
     * @param  Closure(): bool  $callback
     */
    public function customizerAuthorization(Closure $callback): static
    {
        $this->customizerAuthorization = $callback;

        return $this;
    }

    /**
     * Place the appearance page in the navigation.
     */
    public function customizerNavigation(
        string|UnitEnum|null $group = null,
        ?int $sort = null,
        string|BackedEnum|null $icon = null,
    ): static {
        $this->customizerNavigationGroup = $group;
        $this->customizerNavigationSort = $sort;
        $this->customizerNavigationIcon = $icon;

        return $this;
    }

    public function isCustomizerAuthorized(): bool
    {
        if (! $this->customizer) {
            return false;
        }

        return ($this->customizerAuthorization === null)
            || (bool) ($this->customizerAuthorization)();
    }

    public function getCustomizerNavigationGroup(): string|UnitEnum|null
    {
        return $this->customizerNavigationGroup;
    }

    public function getCustomizerNavigationSort(): ?int
    {
        return $this->customizerNavigationSort;
    }

    public function getCustomizerNavigationIcon(): string|BackedEnum|null
    {
        return $this->customizerNavigationIcon;
    }

    /**
     * Offer a language switcher in the user menu, next to the light/dark
     * switch, and apply the visitor's choice to the panel.
     *
     * Off by default, and deliberately so. Setting the locale is not a visual
     * decision: it changes Filament's own copy, the application's copy, and
     * anything else reading `app()->getLocale()` for the length of the
     * request. An application that already decides the language — from the
     * user record, the subdomain, or an `Accept-Language` header — should keep
     * deciding it, and installing a theme should not quietly take that over.
     *
     *     ->localeSwitcher()                              // English, Español
     *     ->localeSwitcher(['en', 'es', 'pt_BR'])
     *     ->localeSwitcher(['en' => 'English (US)', 'es'])
     *     ->localeSwitcher(false)                         // off again
     *
     * Codes must match the directories in the application's `lang` folder.
     * Languages are labelled with their own name unless one is given.
     *
     * Turning this on only affects the panel it is registered in, and only
     * once the visitor picks something: the theme never overrides a locale
     * that nobody asked to change.
     *
     * @param  array<int|string, string>|bool  $locales
     */
    public function localeSwitcher(array|bool $locales = ['en', 'es']): static
    {
        if ($locales === false) {
            $this->locales = [];

            return $this;
        }

        $locales = LocaleLibrary::normalise($locales === true ? ['en', 'es'] : $locales);

        if (count($locales) === 1) {
            throw new InvalidThemeOption(
                'Mía theme: [localeSwitcher] needs at least two locales to switch between. Pass more '
                . 'than one, or leave the switcher off.',
            );
        }

        $this->locales = $locales;

        return $this;
    }

    /**
     * The languages the switcher offers, as `code => label`. Empty when the
     * switcher is off.
     *
     * @return array<string, string>
     */
    public function getLocales(): array
    {
        return $this->locales;
    }

    public function hasLocale(string $locale): bool
    {
        return array_key_exists($locale, $this->locales);
    }

    /**
     * One user menu item per language, which lands directly under the
     * light/dark switch.
     *
     * A list rather than a button that cycles through the languages: the name
     * of every choice stays on screen, which is the point when the visitor
     * cannot read the language the interface is currently in, and adding a
     * third language changes nothing about how it works.
     *
     * Filament's own user menu API, so the theme still overrides no views.
     *
     * @return array<string, Action>
     */
    protected function localeMenuItems(string $panelId): array
    {
        $items = [];

        foreach ($this->locales as $code => $label) {
            $items["mia-locale-{$code}"] = Action::make("mia-locale-{$code}")
                ->label($label)
                // Resolved at render time, after the middleware has applied
                // whatever the visitor last chose.
                ->icon(fn (): Heroicon => app()->getLocale() === $code
                    ? Heroicon::Check
                    : Heroicon::OutlinedLanguage)
                ->color(fn (): string => app()->getLocale() === $code ? 'primary' : 'gray')
                ->url(fn (): string => route('filament-mia.locale', [
                    'panel' => $panelId,
                    'locale' => $code,
                ]));
        }

        return $items;
    }

    /**
     * The plugin's current configuration as a settings record.
     *
     * This is what the customiser starts from before anything has been saved,
     * so the form opens showing the panel as the code configured it.
     */
    public function toSettings(): ThemeSettings
    {
        return new ThemeSettings(
            accentColor: $this->accentColor,
            secondaryColor: $this->secondaryColor,
            neutralColor: $this->neutralColor,
            dangerColor: $this->dangerColor,
            infoColor: $this->infoColor,
            successColor: $this->successColor,
            warningColor: $this->warningColor,
            sansFont: $this->sansFont,
            serifFont: $this->serifFont,
            serifHeadings: $this->serifHeadings,
            roundness: $this->roundness,
            density: $this->density,
            elevation: $this->elevation,
            motion: $this->motion,
            loginLayout: $this->loginLayout,
            loginTagline: $this->loginTagline,
        );
    }

    /**
     * Overlay a settings record onto the plugin.
     */
    public function applySettings(ThemeSettings $settings): static
    {
        $this->accentColor = $settings->accentColor;
        $this->secondaryColor = $settings->secondaryColor;
        $this->neutralColor = $settings->neutralColor;
        $this->dangerColor = $settings->dangerColor;
        $this->infoColor = $settings->infoColor;
        $this->successColor = $settings->successColor;
        $this->warningColor = $settings->warningColor;
        $this->sansFont = $settings->sansFont;
        $this->serifFont = $settings->serifFont;
        $this->serifHeadings = $settings->serifHeadings;
        $this->roundness = $settings->roundness;
        $this->density = $settings->density;
        $this->elevation = $settings->elevation;
        $this->motion = $settings->motion;
        $this->loginLayout = $settings->loginLayout;
        $this->loginTagline = $settings->loginTagline;

        return $this;
    }

    /**
     * Apply whatever the customiser last saved for this panel.
     *
     * Saved settings win over both the config file and the fluent calls above.
     * They have to: they are the most recent deliberate decision, and a panel
     * that ignored what an administrator just saved would be broken. Use the
     * page's reset action to discard the record and return to the code.
     *
     * A stored record is applied whether or not the page is currently enabled,
     * so turning the page off freezes the appearance rather than reverting it.
     */
    protected function applyStoredSettings(string $panelId): void
    {
        $stored = app(SettingsRepository::class)->get($panelId);

        if ($stored === []) {
            return;
        }

        try {
            $this->applySettings(ThemeSettings::fromArray($stored, $this->toSettings()));
        } catch (InvalidThemeOption) {
            // A record hand-edited into an invalid state must not take the
            // panel down, or there would be no way back in to fix it.
        }
    }

    public function statusColors(
        ?string $danger = null,
        ?string $info = null,
        ?string $success = null,
        ?string $warning = null,
    ): static {
        if ($danger !== null) {
            $this->dangerColor = ColorValidator::validate('dangerColor', $danger);
        }

        if ($info !== null) {
            $this->infoColor = ColorValidator::validate('infoColor', $info);
        }

        if ($success !== null) {
            $this->successColor = ColorValidator::validate('successColor', $success);
        }

        if ($warning !== null) {
            $this->warningColor = ColorValidator::validate('warningColor', $warning);
        }

        return $this;
    }
}
