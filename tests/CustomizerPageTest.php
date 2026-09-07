<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Pages\ThemeCustomizer;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JohnRivera7\FilamentMia\Settings\FileSettingsRepository;
use JohnRivera7\FilamentMia\Tests\Fixtures\TestPanelProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * The page's wiring into a real panel.
 *
 * These stop short of rendering the Livewire component: doing that under
 * Orchestra Testbench currently fails inside Livewire's own validation
 * support, for Filament's shipped pages as much as for this one, so a failure
 * there would say nothing about the package. The rendered page is checked
 * against a running application instead — see the README's development notes.
 */
class CustomizerPageTest extends TestCase
{
    protected string $directory;

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), TestPanelProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir() . '/filament-mia-page-' . bin2hex(random_bytes(6));

        $this->app->singleton(
            SettingsRepository::class,
            fn (): FileSettingsRepository => new FileSettingsRepository(new Filesystem, $this->directory),
        );
    }

    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory($this->directory);

        parent::tearDown();
    }

    protected function panel(): Panel
    {
        return Filament::getPanel('testing');
    }

    protected function repository(): SettingsRepository
    {
        return app(SettingsRepository::class);
    }

    #[Test]
    public function enabling_the_customiser_registers_the_page_on_the_panel(): void
    {
        $this->assertContains(ThemeCustomizer::class, $this->panel()->getPages());
    }

    #[Test]
    public function the_page_stays_off_the_panel_until_it_is_asked_for(): void
    {
        $panel = Panel::make()->id('quiet')->path('quiet');

        (new MiaTheme)->register($panel);

        $this->assertNotContains(ThemeCustomizer::class, $panel->getPages());
    }

    #[Test]
    public function a_saved_record_wins_over_the_code(): void
    {
        $this->repository()->put('testing', [
            'accent_color' => '#7C8F5F',
            'roundness' => 'sharp',
        ]);

        $theme = (new MiaTheme)->accentColor('#C9A227');
        $theme->register($this->panel());

        $settings = $theme->toSettings();

        $this->assertSame('#7c8f5f', $settings->accentColor);
        $this->assertSame('sharp', $settings->roundness->value);
    }

    #[Test]
    public function the_code_stands_when_nothing_has_been_saved(): void
    {
        $theme = (new MiaTheme)->accentColor('#C9A227');
        $theme->register($this->panel());

        $this->assertSame('#c9a227', $theme->toSettings()->accentColor);
    }

    #[Test]
    public function a_record_hand_edited_into_nonsense_does_not_take_the_panel_down(): void
    {
        $this->repository()->put('testing', ['accent_color' => 'burnt sienna']);

        $theme = (new MiaTheme)->accentColor('#C9A227');
        $theme->register($this->panel());

        $this->assertSame('#c9a227', $theme->toSettings()->accentColor);
    }

    #[Test]
    public function settings_saved_for_one_panel_do_not_reach_another(): void
    {
        $this->repository()->put('testing', ['accent_color' => '#7C8F5F']);

        $other = Panel::make()->id('other')->path('other');
        $theme = new MiaTheme;
        $theme->register($other);

        $this->assertNotSame('#7c8f5f', $theme->toSettings()->accentColor);
    }
}
