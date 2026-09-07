<?php

namespace JohnRivera7\FilamentMia\Support;

use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;

/**
 * Guards every colour that enters the theme.
 *
 * Filament converts colours to OKLCH with `sscanf`, which fails silently on
 * malformed input and yields a black palette. Validating up front turns that
 * into an explicit, actionable error.
 */
class ColorValidator
{
    private const HEX = '/^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i';

    private const RGB = '/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/i';

    private const TRIPLET = '/^\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*$/';

    private const OKLCH = '/^oklch\(\s*[\d.]+%?\s+[\d.]+%?\s+[\d.]+(?:deg)?\s*\)$/i';

    /**
     * Normalise a user-supplied colour, or throw with a readable message.
     *
     * Three-digit hex is expanded because Filament's converter only reads the
     * six-digit form.
     */
    public static function validate(string $option, string $color): string
    {
        $color = trim($color);

        if ($color === '') {
            throw InvalidThemeOption::forColor($option, $color);
        }

        if (preg_match(self::HEX, $color)) {
            return self::expandShorthandHex($color);
        }

        if (preg_match(self::OKLCH, $color)) {
            return $color;
        }

        foreach ([self::RGB, self::TRIPLET] as $pattern) {
            if (preg_match($pattern, $color, $matches)) {
                return self::assertRgbRange($option, $color, $matches);
            }
        }

        throw InvalidThemeOption::forColor($option, $color);
    }

    private static function expandShorthandHex(string $color): string
    {
        if (strlen($color) !== 4) {
            return strtolower($color);
        }

        [, $r, $g, $b] = str_split(strtolower($color));

        return "#{$r}{$r}{$g}{$g}{$b}{$b}";
    }

    /** @param  array<int, string>  $matches */
    private static function assertRgbRange(string $option, string $color, array $matches): string
    {
        foreach (array_slice($matches, 1) as $channel) {
            if ((int) $channel > 255) {
                throw InvalidThemeOption::forColor($option, $color);
            }
        }

        return sprintf('rgb(%d, %d, %d)', ...array_map('intval', array_slice($matches, 1)));
    }

    /**
     * Font families are interpolated straight into a CSS custom property by
     * Filament, so reject anything that could break out of the declaration.
     */
    public static function validateFontFamily(string $option, string $family): string
    {
        $family = trim($family);

        if ($family === '' || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9 \-]*$/', $family)) {
            throw InvalidThemeOption::forFontFamily($option, $family);
        }

        return $family;
    }
}
