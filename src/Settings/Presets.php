<?php

namespace JohnRivera7\FilamentMia\Settings;

/**
 * Named starting points for the customiser.
 *
 * Each preset is a complete set of design decisions — palette, pairing, shape,
 * density, depth and sign-in composition — rather than a colour swap, because
 * those choices depend on each other: a high-contrast serif wants more air
 * around it, a flat interface needs firmer borders to keep its edges, and a
 * palette this warm carries a brand stage better than a pale one does.
 * Applying a preset fills the form; nothing is saved until the form is.
 *
 * All of them stay inside the theme's warm range. A preset is a variation on
 * Mía, not an escape from it; anything can still be overridden by hand
 * afterwards.
 */
class Presets
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'mia' => [
                'label' => 'Mía',
                'description' => 'Honey gold on cream, set in Cormorant Garamond. The theme as shipped.',
                'settings' => [
                    'accent_color' => '#D9A14E',
                    'secondary_color' => '#E4A987',
                    'neutral_color' => null,
                    'sans_font' => 'Jost',
                    'serif_font' => 'Cormorant Garamond',
                    'serif_headings' => true,
                    'roundness' => 'soft',
                    'density' => 'comfortable',
                    'elevation' => 1.0,
                    'motion' => true,
                    'login_layout' => 'card',
                ],
            ],

            'atelier' => [
                'label' => 'Atelier',
                'description' => 'Terracotta and clay, tighter corners, a soft serif for headings.',
                'settings' => [
                    'accent_color' => '#B4674A',
                    'secondary_color' => '#C9997E',
                    'neutral_color' => null,
                    'sans_font' => 'Outfit',
                    'serif_font' => 'Fraunces',
                    'serif_headings' => true,
                    'roundness' => 'subtle',
                    'density' => 'comfortable',
                    'elevation' => 0.8,
                    'motion' => true,
                    'login_layout' => 'split',
                ],
            ],

            'botanica' => [
                'label' => 'Botanica',
                'description' => 'Sage and wheat, rounder and more spacious, with an old-style serif.',
                'settings' => [
                    'accent_color' => '#788B5C',
                    'secondary_color' => '#C0A96B',
                    'neutral_color' => null,
                    'sans_font' => 'Figtree',
                    'serif_font' => 'EB Garamond',
                    'serif_headings' => true,
                    'roundness' => 'round',
                    'density' => 'spacious',
                    'elevation' => 1.2,
                    'motion' => true,
                    'login_layout' => 'portal',
                ],
            ],

            'papier' => [
                'label' => 'Papier',
                'description' => 'Flat and printed: no shadows, crisp corners, compact rows, Playfair headings.',
                'settings' => [
                    'accent_color' => '#7A6A55',
                    'secondary_color' => '#A8917A',
                    'neutral_color' => null,
                    'sans_font' => 'Work Sans',
                    'serif_font' => 'Playfair Display',
                    'serif_headings' => true,
                    'roundness' => 'sharp',
                    'density' => 'compact',
                    'elevation' => 0.0,
                    'motion' => true,
                    'login_layout' => 'editorial',
                ],
            ],

            'plain' => [
                'label' => 'Plain',
                'description' => 'The warm palette and shapes, but the whole interface in the sans. No serif.',
                'settings' => [
                    'accent_color' => '#D9A14E',
                    'secondary_color' => '#E4A987',
                    'neutral_color' => null,
                    'sans_font' => 'Manrope',
                    'serif_font' => 'Cormorant Garamond',
                    'serif_headings' => false,
                    'roundness' => 'soft',
                    'density' => 'comfortable',
                    'elevation' => 1.0,
                    'motion' => true,
                    'login_layout' => 'card',
                ],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return static::translated('label');
    }

    /**
     * @return array<string, string>
     */
    public static function descriptions(): array
    {
        return static::translated('description');
    }

    /**
     * A preset's name and description in the panel's language.
     *
     * The English text above is the fallback rather than the value: it is what
     * a preset added by an application carries, and what any locale without a
     * published translation falls back to.
     *
     * @return array<string, string>
     */
    protected static function translated(string $key): array
    {
        $translated = [];

        foreach (static::all() as $name => $preset) {
            $line = "filament-mia::customizer.presets.items.{$name}.{$key}";

            $translated[$name] = trans()->has($line)
                ? __($line)
                : $preset[$key];
        }

        return $translated;
    }

    /**
     * The settings a preset applies, or null if the name is not one.
     *
     * @return array<string, mixed>|null
     */
    public static function settings(string $name): ?array
    {
        return static::all()[$name]['settings'] ?? null;
    }

    /**
     * @return array<string>
     */
    public static function names(): array
    {
        return array_keys(static::all());
    }
}
