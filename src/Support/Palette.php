<?php

namespace JohnRivera7\FilamentMia\Support;

use Filament\Support\Colors\Color;

/**
 * Builds the theme's colour ramps.
 *
 * Filament's own `Color::generatePalette()` applies a fixed, fairly saturated
 * chroma curve, which turns a muted champagne into a vivid orange. This builder
 * keeps the hue *and* the chroma character of the colour it is given and only
 * varies lightness, so understated colours stay understated.
 *
 * Lightness stops are chosen so that the shades Filament uses for text and
 * button surfaces (600/700 in light mode, 400 in dark mode) clear WCAG AA
 * against this theme's cream and espresso backgrounds.
 */
class Palette
{
    /**
     * Lightness stop and chroma multiplier per shade.
     *
     * @var array<int, array{float, float}>
     */
    private const RAMP = [
        50 => [0.976, 0.20],
        100 => [0.953, 0.34],
        200 => [0.912, 0.55],
        300 => [0.860, 0.80],
        400 => [0.790, 0.98],
        500 => [0.660, 1.05],
        600 => [0.545, 1.02],
        700 => [0.455, 0.92],
        800 => [0.390, 0.80],
        900 => [0.335, 0.68],
        950 => [0.230, 0.50],
    ];

    /**
     * The warm neutral that carries the whole interface.
     *
     * Light mode draws its page and surface tones from 50–200 and its text from
     * 500–950; dark mode inverts that, using 800–950 for surfaces. Because both
     * modes share one ramp, the dark theme reads as warm espresso rather than
     * the usual blue-grey.
     *
     * @return array<int, string>
     */
    public static function neutral(): array
    {
        return [
            50 => 'oklch(0.977 0.010 80)',
            100 => 'oklch(0.958 0.016 80)',
            200 => 'oklch(0.918 0.022 78)',
            300 => 'oklch(0.858 0.028 76)',
            400 => 'oklch(0.725 0.032 72)',
            500 => 'oklch(0.545 0.031 68)',
            600 => 'oklch(0.462 0.030 65)',
            700 => 'oklch(0.392 0.028 62)',
            800 => 'oklch(0.306 0.024 58)',
            900 => 'oklch(0.240 0.021 55)',
            950 => 'oklch(0.168 0.018 52)',
        ];
    }

    /**
     * Derive an eleven-shade ramp from a single colour, preserving its hue and
     * relative saturation.
     *
     * @return array<int, string>
     */
    public static function from(string $color): array
    {
        [$chroma, $hue] = self::readChromaAndHue($color);

        // Treat near-neutral input as fully neutral so it does not pick up a
        // spurious hue at the darker end of the ramp.
        $isAchromatic = $chroma < 0.02;

        $palette = [];

        foreach (self::RAMP as $shade => [$lightness, $chromaMultiplier]) {
            $shadeChroma = $isAchromatic ? 0.0 : round($chroma * $chromaMultiplier, 4);

            $palette[$shade] = sprintf('oklch(%s %s %s)', $lightness, $shadeChroma, $hue);
        }

        return $palette;
    }

    /** @return array{float, float} */
    private static function readChromaAndHue(string $color): array
    {
        [, $chroma, $hue] = sscanf(Color::convertToOklch($color), 'oklch(%f %f %f)');

        return [(float) $chroma, round((float) $hue, 2)];
    }
}
