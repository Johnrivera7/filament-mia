<?php

namespace JohnRivera7\FilamentMia\Enums;

use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;

/**
 * How much breathing room the interface is given.
 *
 * Density is expressed as a multiplier applied to the theme's own padding and
 * gap tokens. It deliberately does not touch Tailwind's global `--spacing`,
 * which also drives widths and would distort the layout.
 */
enum Density: string
{
    /** Tighter rhythm for data-dense panels. */
    case Compact = 'compact';

    /** The default: airy without wasting space. */
    case Comfortable = 'comfortable';

    /** Editorial spacing, closer to a magazine spread. */
    case Spacious = 'spacious';

    /** @return array<string, string> */
    public function tokens(): array
    {
        return match ($this) {
            self::Compact => [
                'density' => '0.82',
                'row-height' => '2.75rem',
                'section-gap' => '1.25rem',
            ],
            self::Comfortable => [
                'density' => '1',
                'row-height' => '3.5rem',
                'section-gap' => '2rem',
            ],
            self::Spacious => [
                'density' => '1.22',
                'row-height' => '4.25rem',
                'section-gap' => '2.75rem',
            ],
        };
    }

    public static function fromValue(self|string $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::tryFrom(strtolower(trim($value)))
            ?? throw InvalidThemeOption::forEnum('density', $value, self::values());
    }

    /** @return array<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
