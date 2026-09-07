<?php

namespace JohnRivera7\FilamentMia\Support;

use JohnRivera7\FilamentMia\Settings\ThemeSettings;

/**
 * The complete set of custom properties for one settings record.
 *
 * Filament emits colour ramps on `:root` and font families in the layout's
 * `<head>`, both before the `STYLES_AFTER` hook. This class re-emits all of
 * them scoped to the panel, which is more specific *and* later, so the preview
 * wins without `!important`.
 *
 * Nothing here generates a class name. The customiser can only ever change the
 * value of a custom property that the compiled stylesheet already reads, which
 * is what lets the preview be instant and the saved result identical to it.
 */
class PreviewSheet
{
    public function __construct(
        protected string $panelId,
        protected ThemeSettings $settings,
    ) {}

    /**
     * The `<style>` contents: colours, fonts and tokens, light and dark.
     */
    public function css(): string
    {
        $tokens = new TokenSheet(
            panelId: $this->panelId,
            roundness: $this->settings->roundness,
            density: $this->settings->density,
            serifHeadings: $this->settings->serifHeadings,
            motion: $this->settings->motion,
            elevation: $this->settings->elevation,
        );

        return sprintf(
            '%s{%s}%s',
            $tokens->selector(),
            $this->declarations([...$this->colorDeclarations(), ...$this->fontDeclarations()]),
            $tokens->css(),
        );
    }

    /**
     * Bunny Fonts stylesheet URLs for the configured families.
     *
     * The browser needs these before a family change can show. They are the
     * same URLs `BunnyFontProvider` builds, so a previewed family and a saved
     * one load from the same place.
     *
     * @return array<string>
     */
    public function fontUrls(): array
    {
        return array_values(array_unique(array_map(
            fn (string $family): string => sprintf(
                'https://fonts.bunny.net/css?family=%s:400,500,600,700&display=swap',
                str_replace(' ', '-', strtolower($family)),
            ),
            [$this->settings->sansFont, $this->settings->serifFont],
        )));
    }

    /**
     * @return array<string, string>
     */
    protected function colorDeclarations(): array
    {
        $ramps = [
            'primary' => Palette::from($this->settings->accentColor),
            'secondary' => Palette::from($this->settings->secondaryColor),
            'gray' => $this->settings->neutralColor === null
                ? Palette::neutral()
                : Palette::from($this->settings->neutralColor),
            'danger' => Palette::from($this->settings->dangerColor),
            'info' => Palette::from($this->settings->infoColor),
            'success' => Palette::from($this->settings->successColor),
            'warning' => Palette::from($this->settings->warningColor),
        ];

        $declarations = [];

        foreach ($ramps as $name => $shades) {
            foreach ($shades as $shade => $value) {
                $declarations["{$name}-{$shade}"] = $value;
            }
        }

        return $declarations;
    }

    /**
     * @return array<string, string>
     */
    protected function fontDeclarations(): array
    {
        return [
            'font-family' => sprintf("'%s'", $this->settings->sansFont),
            'serif-font-family' => sprintf("'%s'", $this->settings->serifFont),
        ];
    }

    /**
     * @param  array<string, string>  $declarations
     */
    protected function declarations(array $declarations): string
    {
        $css = '';

        foreach ($declarations as $name => $value) {
            $css .= "--{$name}:{$value};";
        }

        return $css;
    }
}
