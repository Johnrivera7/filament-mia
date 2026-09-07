<?php

namespace JohnRivera7\FilamentMia\Settings;

/**
 * Named starting points for the customiser.
 *
 * Each preset is a complete set of design decisions — palette, pairing, shape,
 * density and depth — rather than a colour swap, because those choices depend
 * on each other: a high-contrast serif wants more air around it, and a flat
 * interface needs firmer borders to keep its edges. Applying a preset fills
 * the form; nothing is saved until the form is.
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
                ],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_map(
            fn (array $preset): string => $preset['label'],
            static::all(),
        );
    }

    /**
     * @return array<string, string>
     */
    public static function descriptions(): array
    {
        return array_map(
            fn (array $preset): string => $preset['description'],
            static::all(),
        );
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
