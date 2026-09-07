<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Support\Colors\Color;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Settings\Presets;
use JohnRivera7\FilamentMia\Settings\ThemeSettings;
use JohnRivera7\FilamentMia\Support\FontLibrary;
use JohnRivera7\FilamentMia\Support\Palette;
use JohnRivera7\FilamentMia\Support\PreviewSheet;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class CustomizerTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function presetProvider(): array
    {
        return array_combine(
            Presets::names(),
            array_map(fn (string $name): array => [$name], Presets::names()),
        );
    }

    #[Test]
    #[DataProvider('presetProvider')]
    public function every_preset_is_a_valid_settings_record(string $name): void
    {
        $settings = ThemeSettings::fromArray(Presets::settings($name));

        $this->assertContains($settings->sansFont, FontLibrary::sansFamilies());
        $this->assertContains($settings->serifFont, FontLibrary::serifFamilies());
    }

    /**
     * A preset that shipped failing contrast would be a trap: the interface
     * offers it as a finished choice, so it has to clear AA like the default.
     */
    #[Test]
    #[DataProvider('presetProvider')]
    public function every_preset_accent_stays_legible_in_both_modes(string $name): void
    {
        $settings = ThemeSettings::fromArray(Presets::settings($name));

        $accent = Palette::from($settings->accentColor);
        $neutral = Palette::neutral();

        // Light mode sets accent text at 600 on the cream page.
        $this->assertGreaterThanOrEqual(
            4.5,
            $this->contrast($accent[600], $neutral[50]),
            "The {$name} accent fails AA on the light page.",
        );

        // Dark mode lifts it to 400 against espresso.
        $this->assertGreaterThanOrEqual(
            4.5,
            $this->contrast($accent[400], $neutral[950]),
            "The {$name} accent fails AA on the dark page.",
        );
    }

    #[Test]
    public function the_preview_scopes_every_declaration_to_the_panel(): void
    {
        $css = (new PreviewSheet('admin', ThemeSettings::defaults()))->css();

        $this->assertStringStartsWith('.fi-panel-admin{', $css);
        $this->assertStringContainsString('.dark .fi-panel-admin{', $css);

        // Nothing may leak onto :root, which would recolour every panel.
        $this->assertStringNotContainsString(':root', $css);
    }

    #[Test]
    public function the_preview_emits_the_colour_ramps_and_the_families(): void
    {
        $css = (new PreviewSheet('admin', ThemeSettings::defaults()))->css();

        foreach (['primary', 'secondary', 'gray', 'danger', 'info', 'success', 'warning'] as $ramp) {
            foreach ([50, 500, 950] as $shade) {
                $this->assertStringContainsString("--{$ramp}-{$shade}:oklch(", $css);
            }
        }

        $this->assertStringContainsString("--font-family:'Jost'", $css);
        $this->assertStringContainsString("--serif-font-family:'Cormorant Garamond'", $css);
    }

    #[Test]
    public function the_preview_asks_for_the_families_it_names(): void
    {
        $urls = (new PreviewSheet('admin', ThemeSettings::defaults()))->fontUrls();

        $this->assertContains(
            'https://fonts.bunny.net/css?family=jost:400,500,600,700&display=swap',
            $urls,
        );
        $this->assertContains(
            'https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700&display=swap',
            $urls,
        );
    }

    #[Test]
    public function a_panel_id_cannot_break_out_of_the_preview_selector(): void
    {
        $css = (new PreviewSheet('admin</style><script>', ThemeSettings::defaults()))->css();

        $this->assertStringNotContainsString('<', $css);
        $this->assertStringStartsWith('.fi-panel-adminstylescript{', $css);
    }

    #[Test]
    public function the_customiser_is_off_until_it_is_asked_for(): void
    {
        $this->assertFalse(MiaTheme::make()->isCustomizerAuthorized());
        $this->assertTrue(MiaTheme::make()->customizer()->isCustomizerAuthorized());
    }

    #[Test]
    public function the_customiser_honours_an_authorisation_callback(): void
    {
        $theme = MiaTheme::make()
            ->customizer()
            ->customizerAuthorization(fn (): bool => false);

        $this->assertFalse($theme->isCustomizerAuthorized());
    }

    #[Test]
    public function settings_round_trip_through_the_plugin(): void
    {
        $settings = ThemeSettings::fromArray([
            'accent_color' => '#7C8F5F',
            'roundness' => 'sharp',
            'elevation' => 0.0,
        ]);

        $applied = MiaTheme::make()->applySettings($settings)->toSettings();

        $this->assertSame($settings->toArray(), $applied->toArray());
    }

    protected function contrast(string $a, string $b): float
    {
        return Color::calculateContrastRatio(
            Color::convertToRgb($a),
            Color::convertToRgb($b),
        );
    }
}
