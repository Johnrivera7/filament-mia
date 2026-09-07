<?php

namespace JohnRivera7\FilamentMia\Settings;

use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\LoginLayout;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\Support\ColorValidator;

/**
 * The subset of the theme that the customiser can edit, as a value object.
 *
 * Not every option is here. `sidebarWidth()`, `viteStylesheets()` and the
 * Vite build directory stay code-only: they are wiring rather than design
 * decisions, and an interface that let an administrator repoint a build
 * directory would be a footgun rather than a feature.
 *
 * Every property maps onto a `MiaTheme` setter, so a stored settings record
 * and a fluent call produce exactly the same panel.
 */
class ThemeSettings
{
    /**
     * @param  string|null  $neutralColor  Null keeps the curated warm ramp.
     * @param  string|null  $loginTagline  Shown only by the compositions that
     *                                     stage the brand separately.
     */
    final public function __construct(
        public readonly string $accentColor,
        public readonly string $secondaryColor,
        public readonly ?string $neutralColor,
        public readonly string $dangerColor,
        public readonly string $infoColor,
        public readonly string $successColor,
        public readonly string $warningColor,
        public readonly string $sansFont,
        public readonly string $serifFont,
        public readonly bool $serifHeadings,
        public readonly Roundness $roundness,
        public readonly Density $density,
        public readonly float $elevation,
        public readonly bool $motion,
        public readonly LoginLayout $loginLayout,
        public readonly ?string $loginTagline,
    ) {}

    /**
     * Build from a loosely typed array, validating every value.
     *
     * Anything missing falls back to `$fallback`, which lets a stored record
     * written by an older version of the package — one that did not know about
     * a setting added since — load without error.
     *
     * @param  array<string, mixed>  $values
     *
     * @throws InvalidThemeOption
     */
    public static function fromArray(array $values, ?self $fallback = null): static
    {
        $fallback ??= static::defaults();

        $neutral = array_key_exists('neutral_color', $values)
            ? $values['neutral_color']
            : $fallback->neutralColor;

        return new static(
            accentColor: ColorValidator::validate('accentColor', $values['accent_color'] ?? $fallback->accentColor),
            secondaryColor: ColorValidator::validate('secondaryColor', $values['secondary_color'] ?? $fallback->secondaryColor),
            neutralColor: blank($neutral) ? null : ColorValidator::validate('neutralColor', $neutral),
            dangerColor: ColorValidator::validate('dangerColor', $values['danger_color'] ?? $fallback->dangerColor),
            infoColor: ColorValidator::validate('infoColor', $values['info_color'] ?? $fallback->infoColor),
            successColor: ColorValidator::validate('successColor', $values['success_color'] ?? $fallback->successColor),
            warningColor: ColorValidator::validate('warningColor', $values['warning_color'] ?? $fallback->warningColor),
            sansFont: ColorValidator::validateFontFamily('font', $values['sans_font'] ?? $fallback->sansFont),
            serifFont: ColorValidator::validateFontFamily('font', $values['serif_font'] ?? $fallback->serifFont),
            serifHeadings: (bool) ($values['serif_headings'] ?? $fallback->serifHeadings),
            roundness: Roundness::fromValue($values['roundness'] ?? $fallback->roundness),
            density: Density::fromValue($values['density'] ?? $fallback->density),
            elevation: max(0.0, min(2.0, (float) ($values['elevation'] ?? $fallback->elevation))),
            motion: (bool) ($values['motion'] ?? $fallback->motion),
            loginLayout: LoginLayout::fromValue($values['login_layout'] ?? $fallback->loginLayout),
            loginTagline: static::normaliseTagline(
                array_key_exists('login_tagline', $values)
                    ? $values['login_tagline']
                    : $fallback->loginTagline,
            ),
        );
    }

    /**
     * The shipped defaults, as configured in `config/filament-mia.php`.
     *
     * Colours go through the same validator as everything else, so a record
     * built from the config file and one built from the customiser normalise
     * identically and a save that changed nothing produces no diff.
     */
    public static function defaults(): static
    {
        $config = config('filament-mia', []);

        $neutral = $config['colors']['neutral'] ?? null;

        return new static(
            accentColor: ColorValidator::validate('accentColor', $config['colors']['accent'] ?? '#D9A14E'),
            secondaryColor: ColorValidator::validate('secondaryColor', $config['colors']['secondary'] ?? '#E4A987'),
            neutralColor: blank($neutral) ? null : ColorValidator::validate('neutralColor', $neutral),
            dangerColor: ColorValidator::validate('dangerColor', $config['colors']['danger'] ?? '#C1614F'),
            infoColor: ColorValidator::validate('infoColor', $config['colors']['info'] ?? '#8B9FB0'),
            successColor: ColorValidator::validate('successColor', $config['colors']['success'] ?? '#8A9A6B'),
            warningColor: ColorValidator::validate('warningColor', $config['colors']['warning'] ?? '#D9A441'),
            sansFont: ColorValidator::validateFontFamily('font', $config['fonts']['sans'] ?? 'Jost'),
            serifFont: ColorValidator::validateFontFamily('font', $config['fonts']['serif'] ?? 'Cormorant Garamond'),
            serifHeadings: (bool) ($config['typography']['serif_headings'] ?? true),
            roundness: Roundness::fromValue($config['roundness'] ?? Roundness::Soft),
            density: Density::fromValue($config['density'] ?? Density::Comfortable),
            elevation: (float) ($config['elevation'] ?? 1.0),
            motion: (bool) ($config['motion'] ?? true),
            loginLayout: LoginLayout::fromValue($config['login']['layout'] ?? LoginLayout::Card),
            loginTagline: static::normaliseTagline($config['login']['tagline'] ?? null),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'accent_color' => $this->accentColor,
            'secondary_color' => $this->secondaryColor,
            'neutral_color' => $this->neutralColor,
            'danger_color' => $this->dangerColor,
            'info_color' => $this->infoColor,
            'success_color' => $this->successColor,
            'warning_color' => $this->warningColor,
            'sans_font' => $this->sansFont,
            'serif_font' => $this->serifFont,
            'serif_headings' => $this->serifHeadings,
            'roundness' => $this->roundness->value,
            'density' => $this->density->value,
            'elevation' => $this->elevation,
            'motion' => $this->motion,
            'login_layout' => $this->loginLayout->value,
            'login_tagline' => $this->loginTagline,
        ];
    }

    /**
     * A single line of copy, or null.
     *
     * Trimmed and capped rather than validated against a pattern: this is the
     * one setting whose value is prose, and the cap is what keeps a paragraph
     * pasted into the field from breaking the composition it appears in.
     */
    protected static function normaliseTagline(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '');

        return blank($value) ? null : mb_substr($value, 0, 120);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function with(array $values): static
    {
        return static::fromArray($values, $this);
    }
}
