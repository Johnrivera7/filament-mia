<?php

namespace JohnRivera7\FilamentMia\Enums;

use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;

/**
 * Corner treatment for surfaces, controls and inputs.
 *
 * Each case maps onto the Tailwind radius scale that the compiled stylesheet
 * consumes, so switching roundness never requires a rebuild.
 */
enum Roundness: string
{
    /** Crisp corners, for a more typographic, print-like feel. */
    case Sharp = 'sharp';

    /** Restrained rounding. */
    case Subtle = 'subtle';

    /** The default: generous but still architectural. */
    case Soft = 'soft';

    /** Pronounced rounding, closer to a consumer product. */
    case Round = 'round';

    /** @return array<string, string> */
    public function tokens(): array
    {
        return match ($this) {
            self::Sharp => [
                'radius-xs' => '0.0625rem',
                'radius-sm' => '0.125rem',
                'radius-md' => '0.1875rem',
                'radius-lg' => '0.25rem',
                'radius-xl' => '0.375rem',
                'radius-2xl' => '0.5rem',
                'radius-3xl' => '0.625rem',
            ],
            self::Subtle => [
                'radius-xs' => '0.1875rem',
                'radius-sm' => '0.25rem',
                'radius-md' => '0.375rem',
                'radius-lg' => '0.5rem',
                'radius-xl' => '0.625rem',
                'radius-2xl' => '0.875rem',
                'radius-3xl' => '1.125rem',
            ],
            self::Soft => [
                'radius-xs' => '0.25rem',
                'radius-sm' => '0.375rem',
                'radius-md' => '0.5rem',
                'radius-lg' => '0.75rem',
                'radius-xl' => '0.9375rem',
                'radius-2xl' => '1.25rem',
                'radius-3xl' => '1.75rem',
            ],
            self::Round => [
                'radius-xs' => '0.375rem',
                'radius-sm' => '0.5rem',
                'radius-md' => '0.75rem',
                'radius-lg' => '1.125rem',
                'radius-xl' => '1.5rem',
                'radius-2xl' => '1.875rem',
                'radius-3xl' => '2.5rem',
            ],
        };
    }

    /**
     * The same decision expressed for a canvas.
     *
     * Chart.js is handed plain numbers, not CSS lengths, so these cannot live
     * on the radius scale above. The values track it all the same: the tooltip
     * radius is `radius-lg` in pixels, and line curvature moves with the
     * corners, from a polyline at `sharp` to a flowing curve at `round`.
     *
     * @return array<string, string>
     */
    public function chartTokens(): array
    {
        return match ($this) {
            self::Sharp => [
                'chart-line-tension' => '0',
                'chart-bar-radius' => '0',
                'chart-tooltip-radius' => '4',
                'chart-legend-swatch-radius' => '0',
            ],
            self::Subtle => [
                'chart-line-tension' => '0.2',
                'chart-bar-radius' => '2',
                'chart-tooltip-radius' => '8',
                'chart-legend-swatch-radius' => '2',
            ],
            self::Soft => [
                'chart-line-tension' => '0.35',
                'chart-bar-radius' => '4',
                'chart-tooltip-radius' => '12',
                'chart-legend-swatch-radius' => '3',
            ],
            self::Round => [
                'chart-line-tension' => '0.45',
                'chart-bar-radius' => '8',
                'chart-tooltip-radius' => '18',
                'chart-legend-swatch-radius' => '5',
            ],
        };
    }

    public static function fromValue(self|string $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::tryFrom(strtolower(trim($value)))
            ?? throw InvalidThemeOption::forEnum('roundness', $value, self::values());
    }

    /** @return array<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
