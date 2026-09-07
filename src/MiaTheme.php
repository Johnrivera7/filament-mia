<?php

namespace JohnRivera7\FilamentMia;

use Filament\Contracts\Plugin;
use Filament\FontProviders\BunnyFontProvider;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Support\ColorValidator;
use JohnRivera7\FilamentMia\Support\Palette;
use JohnRivera7\FilamentMia\Support\TokenSheet;

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
