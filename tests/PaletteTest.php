<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Support\Colors\Color;
use JohnRivera7\FilamentMia\Support\Palette;

class PaletteTest extends TestCase
{
    private const SHADES = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];

    public function test_a_palette_covers_every_shade_filament_expects(): void
    {
        $palette = Palette::from('#D9A14E');

        foreach (self::SHADES as $shade) {
            $this->assertArrayHasKey($shade, $palette);
            $this->assertStringStartsWith('oklch(', $palette[$shade]);
        }
    }

    public function test_lightness_decreases_monotonically(): void
    {
        $palette = Palette::from('#D9A14E');
        $previous = 1.0;

        foreach (self::SHADES as $shade) {
            preg_match('/oklch\(([0-9.]+)/', $palette[$shade], $matches);
            $lightness = (float) $matches[1];

            $this->assertLessThan(
                $previous,
                $lightness,
                "Shade {$shade} is not darker than the shade before it.",
            );

            $previous = $lightness;
        }
    }

    /**
     * A ramp built from an understated colour should stay understated. Scaling
     * the input's own chroma, rather than snapping to a fixed value, is what
     * keeps a muted accent from being pushed to full saturation.
     */
    public function test_a_muted_input_produces_a_muted_ramp(): void
    {
        $muted = Palette::from('#C0A98F');
        $vivid = Palette::from('#FF6A00');

        $this->assertLessThan(
            $this->chroma($vivid[500]),
            $this->chroma($muted[500]),
            'A desaturated input should not yield a ramp as saturated as a vivid one.',
        );
    }

    public function test_the_hue_of_the_input_is_preserved(): void
    {
        $palette = Palette::from('#D9A14E');
        $expected = $this->hue(Color::convertToOklch('#D9A14E'));

        foreach ([300, 500, 700] as $shade) {
            $this->assertEqualsWithDelta(
                $expected,
                $this->hue($palette[$shade]),
                0.5,
                "Shade {$shade} has drifted away from the input hue.",
            );
        }
    }

    /**
     * These are the exact pairings the stylesheet relies on, so a change to
     * the ramp that broke accessibility would fail here rather than in
     * someone's panel.
     */
    public function test_the_default_accent_meets_wcag_aa_where_the_theme_uses_it(): void
    {
        $accent = Palette::from('#D9A14E');
        $neutral = Palette::neutral();

        $pairs = [
            'accent 700 text on the light surface' => [$accent[700], $neutral[50]],
            'white text on the accent 600 button' => ['oklch(1 0 0)', $accent[600]],
            'accent 300 text on the dark card' => [$accent[300], $neutral[900]],
        ];

        foreach ($pairs as $label => [$foreground, $background]) {
            $ratio = Color::calculateContrastRatio($foreground, $background);

            $this->assertGreaterThanOrEqual(
                Color::WCAG_AA_TEXT,
                $ratio,
                sprintf('%s is only %.2f:1.', $label, $ratio),
            );
        }
    }

    public function test_the_neutral_ramp_is_warm_at_both_ends(): void
    {
        $neutral = Palette::neutral();

        // A warm neutral keeps a non-zero chroma; a cool grey would be at zero.
        foreach ([50, 950] as $shade) {
            $this->assertGreaterThan(
                0.0,
                $this->chroma($neutral[$shade]),
                "Neutral {$shade} has no chroma, so it is a plain grey.",
            );
        }
    }

    private function chroma(string $oklch): float
    {
        preg_match('/oklch\([0-9.]+ ([0-9.]+)/', $oklch, $matches);

        return (float) ($matches[1] ?? 0);
    }

    private function hue(string $oklch): float
    {
        preg_match('/oklch\([0-9.]+ [0-9.]+ ([0-9.]+)/', $oklch, $matches);

        return (float) ($matches[1] ?? 0);
    }
}
