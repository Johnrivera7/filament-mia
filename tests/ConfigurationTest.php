<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\MiaTheme;

class ConfigurationTest extends TestCase
{
    public function test_the_plugin_reports_a_stable_id(): void
    {
        // Applications retrieve the plugin by this id, so it is public API.
        $this->assertSame('mia-theme', MiaTheme::make()->getId());
    }

    public function test_the_fluent_api_is_chainable(): void
    {
        $theme = MiaTheme::make()
            ->accentColor('#C9A227')
            ->secondaryColor('#E8C4C0')
            ->font('Jost', 'Cormorant Garamond')
            ->roundness('soft')
            ->density('comfortable')
            ->elevation(0.5)
            ->motion(false)
            ->darkMode();

        $this->assertInstanceOf(MiaTheme::class, $theme);
    }

    /**
     * Filament converts colours without validating them, so an unparseable
     * value yields a black palette and no error at all. Rejecting the input
     * up front is the only way the mistake becomes visible.
     */
    public function test_an_unparseable_colour_is_rejected(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->accentColor('champagne');
    }

    public function test_a_three_digit_hex_colour_is_accepted(): void
    {
        $theme = MiaTheme::make()->accentColor('#DA4');

        $this->assertInstanceOf(MiaTheme::class, $theme);
    }

    public function test_an_oklch_colour_is_accepted(): void
    {
        $theme = MiaTheme::make()->accentColor('oklch(0.66 0.12 75)');

        $this->assertInstanceOf(MiaTheme::class, $theme);
    }

    public function test_an_unknown_roundness_is_rejected(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->roundness('squircle');
    }

    public function test_an_unknown_density_is_rejected(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->density('cramped');
    }

    /**
     * Font families reach the stylesheet inside a CSS custom property, so a
     * quote or a semicolon in the name would break out of the declaration.
     */
    public function test_a_font_family_containing_css_syntax_is_rejected(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->font('Jost"; --x: y');
    }

    public function test_every_roundness_defines_the_full_radius_scale(): void
    {
        foreach (Roundness::cases() as $roundness) {
            $tokens = $roundness->tokens();

            foreach (['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl'] as $step) {
                $this->assertArrayHasKey(
                    "radius-{$step}",
                    $tokens,
                    "Roundness '{$roundness->value}' is missing the {$step} radius.",
                );
            }
        }
    }

    public function test_every_density_defines_its_scale_and_row_height(): void
    {
        foreach (Density::cases() as $density) {
            $tokens = $density->tokens();

            $this->assertArrayHasKey('density', $tokens);
            $this->assertArrayHasKey('row-height', $tokens);
            $this->assertArrayHasKey('section-gap', $tokens);
        }
    }
}
