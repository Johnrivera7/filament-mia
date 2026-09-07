<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\Enums\LoginLayout;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Settings\Presets;
use JohnRivera7\FilamentMia\Settings\ThemeSettings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class LoginLayoutTest extends TestCase
{
    #[Test]
    public function the_quietest_composition_is_the_default(): void
    {
        $this->assertSame(
            LoginLayout::Card,
            MiaTheme::make()->toSettings()->loginLayout,
        );
    }

    #[Test]
    #[DataProvider('layouts')]
    public function every_composition_can_be_set_by_name(string $value): void
    {
        $this->assertSame(
            $value,
            MiaTheme::make()->loginLayout($value)->toSettings()->loginLayout->value,
        );
    }

    /** @return array<array<string>> */
    public static function layouts(): array
    {
        return array_map(
            fn (string $value): array => [$value],
            LoginLayout::values(),
        );
    }

    #[Test]
    public function an_unknown_composition_is_refused_rather_than_ignored(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->loginLayout('carousel');
    }

    /**
     * The stage is markup the plugin injects, so only the compositions that
     * have somewhere to put it should ask for it. Getting this wrong would
     * leave an empty flex item in the layout of the other three.
     */
    #[Test]
    public function only_the_two_asymmetric_compositions_carry_a_stage(): void
    {
        $withStage = array_values(array_filter(
            LoginLayout::cases(),
            fn (LoginLayout $layout): bool => $layout->hasStage(),
        ));

        $this->assertSame([LoginLayout::Split, LoginLayout::Editorial], $withStage);
    }

    /**
     * Only one composition moves the brand mark onto the stage, and it is the
     * only one whose stage is therefore part of the accessibility tree. The
     * other stage repeats a name that is already above the form.
     */
    #[Test]
    public function one_composition_moves_the_brand_mark_onto_the_stage(): void
    {
        $this->assertTrue(LoginLayout::Split->movesBrandToStage());
        $this->assertFalse(LoginLayout::Editorial->movesBrandToStage());
    }

    #[Test]
    public function the_composition_survives_a_round_trip_through_a_settings_record(): void
    {
        $settings = ThemeSettings::defaults()->with([
            'login_layout' => 'editorial',
            'login_tagline' => 'Client work, kept in one place.',
        ]);

        $restored = ThemeSettings::fromArray($settings->toArray());

        $this->assertSame(LoginLayout::Editorial, $restored->loginLayout);
        $this->assertSame('Client work, kept in one place.', $restored->loginTagline);
    }

    #[Test]
    public function a_tagline_is_reduced_to_a_single_line_and_capped(): void
    {
        $settings = ThemeSettings::defaults()->with([
            'login_tagline' => "  Two   lines\n  of copy  ",
        ]);

        $this->assertSame('Two lines of copy', $settings->loginTagline);

        $this->assertSame(
            120,
            mb_strlen(ThemeSettings::defaults()->with([
                'login_tagline' => str_repeat('a', 400),
            ])->loginTagline ?? ''),
        );
    }

    #[Test]
    public function an_empty_tagline_is_stored_as_nothing_rather_than_as_a_blank(): void
    {
        $this->assertNull(
            ThemeSettings::defaults()->with(['login_tagline' => '   '])->loginTagline,
        );
    }

    /**
     * A preset is a complete set of design decisions, so leaving one of them
     * out would carry the previous composition into a new look.
     */
    #[Test]
    public function every_preset_names_a_composition(): void
    {
        foreach (Presets::names() as $name) {
            $settings = Presets::settings($name);

            $this->assertArrayHasKey('login_layout', $settings, $name);

            $this->assertContains(
                $settings['login_layout'],
                LoginLayout::values(),
                $name,
            );
        }
    }
}
