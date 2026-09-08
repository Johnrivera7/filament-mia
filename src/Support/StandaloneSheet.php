<?php

namespace JohnRivera7\FilamentMia\Support;

use JohnRivera7\FilamentMia\Settings\ThemeSettings;

/**
 * The complete stylesheet for a page the theme has to draw with no panel
 * around it: an error page, or the maintenance page.
 *
 * Everything a panel normally supplies is missing on these pages. Filament
 * emits its OKLCH ramps and font families from inside a panel render, and the
 * plugin injects the `--mia-*` tokens through a panel render hook, so the
 * compiled theme on its own would resolve to a stylesheet with no colours in
 * it. This class supplies all of it as literal values instead, and hands back
 * one self-contained `<style>` body.
 *
 * Read from `config/filament-mia.php` and nothing else — not the settings the
 * appearance page saves to disk, not the session, not the database, not the
 * signed-in user. That is a requirement rather than a simplification: the
 * maintenance page is rendered ahead of time by `php artisan down --render`
 * and then served straight out of `storage/framework/maintenance.php`, before
 * the framework is up. A page that needed any of those would be a page that
 * could not be served at the one moment it exists for.
 *
 * The consequence is worth stating plainly: a look tuned in the appearance
 * page and saved shows on the panel but not on these pages, which follow the
 * config file. Put the decisions you want a 404 to inherit in the config file.
 */
class StandaloneSheet
{
    public function __construct(
        protected ThemeSettings $settings,
    ) {}

    public static function fromConfig(): static
    {
        return new static(ThemeSettings::defaults());
    }

    /**
     * The `<style>` contents: the generated tokens, then the hand-written
     * rules from `resources/css/standalone.css`.
     */
    public function css(): string
    {
        return $this->tokens() . $this->rules();
    }

    /**
     * Bunny Fonts stylesheet URL for the configured families.
     *
     * The same URL `BunnyFontProvider` builds for the panel, so a standalone
     * page and the panel it belongs to load their type from one place. Every
     * rule that uses it also names a system fallback, so a page rendered with
     * no network reachable still reads correctly — it just reads in Georgia.
     */
    public function fontUrl(): string
    {
        $families = array_map(
            fn (string $family): string => str_replace(' ', '-', strtolower($family)) . ':400,500,600',
            array_values(array_unique([$this->settings->sansFont, $this->settings->serifFont])),
        );

        return 'https://fonts.bunny.net/css?family=' . implode('|', $families) . '&display=swap';
    }

    /**
     * The three-line script that matches a standalone page to the light or
     * dark mode the visitor last chose in the panel.
     *
     * Filament keeps that choice in `localStorage` under `theme`, which is a
     * client-side store and therefore still readable when the server that
     * would normally answer for it is down or has just thrown. Reading it here
     * is what stops a 404 from arriving in the wrong mode.
     *
     * The CSS resolves `prefers-color-scheme` on its own, so this script is an
     * upgrade and never a dependency: with JavaScript off, a Content Security
     * Policy that forbids inline scripts, or nothing stored yet, the page
     * still picks a mode — the operating system's. `localStorage` is read
     * inside a `try` because it throws rather than returning null in some
     * privacy modes.
     */
    public function schemeScript(): string
    {
        return <<<'JS'
        try {
            var mia = localStorage.getItem('theme');

            if (mia === 'light' || mia === 'dark') {
                document.documentElement.dataset.miaScheme = mia;
            }
        } catch (e) {}
        JS;
    }

    /**
     * The token declarations, in three blocks.
     *
     * Light is the base. Dark is emitted twice, once for an explicit stored
     * preference and once for the operating system's, and the second is
     * qualified with `:not([data-mia-scheme='light'])` so that a visitor who
     * chose light in the panel keeps light on a dark desktop.
     *
     * The two dark selectors have identical specificity, so source order is
     * what settles them; the media query therefore has to come last.
     */
    protected function tokens(): string
    {
        $panelTokens = new TokenSheet(
            panelId: 'mia-standalone',
            roundness: $this->settings->roundness,
            density: $this->settings->density,
            serifHeadings: $this->settings->serifHeadings,
            motion: $this->settings->motion,
            elevation: $this->settings->elevation,
        );

        $light = $this->declarations([
            ...$this->colorTokens(),
            ...$this->fontTokens(),
            ...$panelTokens->lightTokens(),
            ...$this->lightTokens(),
        ]);

        $dark = $this->declarations([
            ...$panelTokens->darkTokens(),
            ...$this->darkTokens(),
        ]);

        return implode('', [
            ":root{color-scheme:light;{$light}}",
            ":root[data-mia-scheme='dark']{color-scheme:dark;{$dark}}",
            "@media (prefers-color-scheme: dark){:root:not([data-mia-scheme='light']){color-scheme:dark;{$dark}}}",
        ]);
    }

    /**
     * The colour ramps as literal OKLCH, since there is no Filament runtime
     * here to emit them.
     *
     * Only the three ramps a standalone page can reach: the accent, the
     * supporting colour that closes the illustration's gradient, and the
     * neutral that everything else is built from. The status ramps have
     * nowhere to appear on a page with no data on it.
     *
     * @return array<string, string>
     */
    protected function colorTokens(): array
    {
        $ramps = [
            'primary' => Palette::from($this->settings->accentColor),
            'secondary' => Palette::from($this->settings->secondaryColor),
            'gray' => $this->settings->neutralColor === null
                ? Palette::neutral()
                : Palette::from($this->settings->neutralColor),
        ];

        $tokens = [];

        foreach ($ramps as $name => $shades) {
            foreach ($shades as $shade => $value) {
                $tokens["{$name}-{$shade}"] = $value;
            }
        }

        return $tokens;
    }

    /**
     * @return array<string, string>
     */
    protected function fontTokens(): array
    {
        return [
            'font-family' => sprintf("'%s'", $this->settings->sansFont),
            'serif-font-family' => sprintf("'%s'", $this->settings->serifFont),
        ];
    }

    /**
     * What a standalone page needs on top of the panel's own token set.
     *
     * @return array<string, string>
     */
    protected function lightTokens(): array
    {
        return [
            'mia-sprig' => $this->sprig(),
            'mia-grain' => $this->grain('var(--gray-950)', 1.0, 0.7),
            'mia-standalone-field' => $this->field('var(--primary-200)', 46, 'var(--secondary-200)', 40),
            'mia-standalone-halo' => $this->halo('var(--primary-200)', 62, 'var(--primary-100)', 38),

            // The one-pixel highlight that makes the card read as lit rather
            // than as a rectangle with a border.
            'mia-standalone-lip' => 'color-mix(in oklab, white 55%, transparent)',

            /*
             * White on the 600 shade, which is the pairing the theme already
             * measures for accent buttons; the ramp's lightness stops are
             * chosen so that it clears AA for any hue the accent can take.
             */
            'mia-standalone-button-ink' => 'white',
            'mia-standalone-button-face' => 'var(--primary-600)',
            'mia-standalone-button-face-hover' => 'var(--primary-700)',
            'mia-standalone-ring' => 'var(--primary-600)',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function darkTokens(): array
    {
        return [
            'mia-grain' => $this->grain('white', 1.0, 0.7),
            'mia-standalone-field' => $this->field('var(--primary-500)', 12, 'var(--secondary-500)', 9),
            'mia-standalone-halo' => $this->halo('var(--primary-500)', 22, 'var(--primary-500)', 10),
            'mia-standalone-lip' => 'color-mix(in oklab, white 5%, transparent)',

            // Dark mode inverts the accent button rather than dimming it: the
            // darkest neutral on the 400 shade, as elsewhere in the theme.
            'mia-standalone-button-ink' => 'var(--gray-950)',
            'mia-standalone-button-face' => 'var(--primary-400)',
            'mia-standalone-button-face-hover' => 'var(--primary-300)',
            'mia-standalone-ring' => 'var(--primary-400)',
        ];
    }

    /**
     * The theme's botanical sprig as a data URI, for use as a CSS mask.
     *
     * Built from `resources/images/sprig.svg` so the drawing has one source.
     * The `viewBox` is retightened to the artwork's own bounds, because the
     * file is cropped for use as a square image and the mask has to fill its
     * container the way the empty state's copy does.
     */
    protected function sprig(): string
    {
        $svg = @file_get_contents(__DIR__ . '/../../resources/images/sprig.svg');

        if ($svg === false) {
            return 'none';
        }

        // Comments and indentation would survive the encoding and be paid for
        // on every request the page is served.
        $svg = preg_replace('/<!--.*?-->/s', '', $svg) ?? $svg;
        $svg = preg_replace('/\s+/', ' ', trim($svg)) ?? $svg;
        $svg = str_replace('viewBox="0 0 120 120"', 'viewBox="13 25 79 87"', $svg);

        return sprintf('url("data:image/svg+xml,%s")', rawurlencode($svg));
    }

    /**
     * A fine warm grain: two hairline gratings at barely-there opacity, which
     * read as paper rather than as pattern. Lifted from `auth.css`, where the
     * sign-in compositions lay the same texture over their gradients.
     */
    protected function grain(string $ink, float $horizontal, float $vertical): string
    {
        return sprintf(
            'repeating-linear-gradient(0deg, color-mix(in oklab, %1$s %2$s%%, transparent) 0 1px, transparent 1px 5px),'
            . 'repeating-linear-gradient(90deg, color-mix(in oklab, %1$s %3$s%%, transparent) 0 1px, transparent 1px 5px)',
            $ink,
            $horizontal,
            $vertical,
        );
    }

    /**
     * Two soft pools of warm light over the canvas, the same field the `card`
     * sign-in composition stands on.
     */
    protected function field(string $first, int $firstMix, string $second, int $secondMix): string
    {
        return sprintf(
            'var(--mia-grain),'
            . 'radial-gradient(60%% 50%% at 22%% 18%%, color-mix(in oklab, %s %d%%, transparent) 0%%, transparent 68%%),'
            . 'radial-gradient(55%% 45%% at 82%% 82%%, color-mix(in oklab, %s %d%%, transparent) 0%%, transparent 70%%)',
            $first,
            $firstMix,
            $second,
            $secondMix,
        );
    }

    /**
     * The warm halo behind the illustration, sized and tinted as the empty
     * state's is.
     */
    protected function halo(string $core, int $coreMix, string $edge, int $edgeMix): string
    {
        return sprintf(
            'radial-gradient(circle at 50%% 42%%, color-mix(in oklab, %s %d%%, transparent) 0%%,'
            . ' color-mix(in oklab, %s %d%%, transparent) 58%%, transparent 72%%)',
            $core,
            $coreMix,
            $edge,
            $edgeMix,
        );
    }

    /**
     * The hand-written rules, stripped of the comments that explain them to
     * whoever maintains the theme rather than to the browser.
     */
    protected function rules(): string
    {
        $css = @file_get_contents(__DIR__ . '/../../resources/css/standalone.css');

        if ($css === false) {
            return '';
        }

        $css = preg_replace('#/\*.*?\*/#s', '', $css) ?? $css;

        return trim(preg_replace('/\n\s*\n/', "\n", $css) ?? $css);
    }

    /** @param  array<string, string>  $tokens */
    protected function declarations(array $tokens): string
    {
        $css = '';

        foreach ($tokens as $name => $value) {
            $css .= "--{$name}:{$value};";
        }

        return $css;
    }
}
