<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;
use JohnRivera7\FilamentMia\Pages\PageBuilder;
use PHPUnit\Framework\Attributes\Test;

/**
 * The feature's wiring into a panel, and its silence when nobody asked for it.
 *
 * These stop short of rendering the Livewire component, as the appearance
 * page's tests do: doing that under Orchestra Testbench currently fails inside
 * Livewire's own validation support, for Filament's shipped pages as much as
 * for this one, so a failure there would say nothing about the package. What
 * the builder writes and what the public page renders are covered separately.
 */
class PageBuilderTest extends TestCase
{
    #[Test]
    public function the_page_stays_off_the_panel_until_it_is_asked_for(): void
    {
        $panel = Panel::make()->id('quiet')->path('quiet');

        (new MiaTheme)->register($panel);

        $this->assertNotContains(PageBuilder::class, $panel->getPages());
    }

    #[Test]
    public function asking_for_it_registers_the_page(): void
    {
        $panel = Panel::make()->id('loud')->path('loud');

        (new MiaTheme)->pageBuilder()->register($panel);

        $this->assertContains(PageBuilder::class, $panel->getPages());
    }

    /**
     * The public route and the preview both belong to the feature, so a
     * panel that never switched it on must not have either. This test class
     * registers no panel with the builder, which is what makes the assertion
     * worth making.
     */
    #[Test]
    public function no_route_is_registered_when_the_feature_is_off(): void
    {
        $this->assertFalse(Route::has('filament-mia.page'));
        $this->assertFalse(Route::has('filament-mia.page.preview'));
    }

    #[Test]
    public function nothing_answers_for_the_builder_when_no_panel_enabled_it(): void
    {
        $this->assertNull(PageBuilderPanel::resolve());
    }

    #[Test]
    public function the_feature_is_off_in_the_shipped_config(): void
    {
        $this->assertFalse(config('filament-mia.page_builder.enabled'));
        $this->assertFalse((new MiaTheme)->hasPageBuilder());
    }

    #[Test]
    public function the_migration_is_offered_for_publishing_rather_than_loaded(): void
    {
        // Loaded migrations would show up in the migrator's own paths. The
        // stub has to be published and run deliberately, so it must not.
        $this->assertNotContains(
            realpath(__DIR__ . '/../database/migrations'),
            array_map('realpath', app('migrator')->paths()),
        );

        $this->assertFileExists(__DIR__ . '/../database/migrations/create_mia_pages_table.php.stub');
    }

    #[Test]
    public function the_config_file_can_switch_the_feature_on(): void
    {
        config()->set('filament-mia.page_builder.enabled', true);

        $this->assertTrue((new MiaTheme)->hasPageBuilder());
    }

    #[Test]
    public function the_public_path_defaults_to_the_site_root(): void
    {
        $this->assertSame('/', (new MiaTheme)->pageBuilder()->getPageBuilderPath());
    }

    #[Test]
    public function a_given_path_is_normalised_to_one_without_slashes_around_it(): void
    {
        $theme = (new MiaTheme)->pageBuilder(path: '/welcome/');

        $this->assertSame('welcome', $theme->getPageBuilderPath());
    }

    #[Test]
    public function authorisation_defaults_to_anyone_who_can_reach_the_panel(): void
    {
        $this->assertTrue((new MiaTheme)->pageBuilder()->isPageBuilderAuthorized());
    }

    #[Test]
    public function the_authorisation_callback_can_refuse(): void
    {
        $theme = (new MiaTheme)
            ->pageBuilder()
            ->pageBuilderAuthorization(fn (): bool => false);

        $this->assertFalse($theme->isPageBuilderAuthorized());
    }

    /**
     * Belt and braces: with the feature off, the callback is never consulted
     * and the answer is still no. Otherwise a permissive callback left behind
     * after switching the builder off would read as authorisation.
     */
    #[Test]
    public function nobody_is_authorised_while_the_feature_is_off(): void
    {
        $theme = (new MiaTheme)->pageBuilderAuthorization(fn (): bool => true);

        $this->assertFalse($theme->isPageBuilderAuthorized());
    }

    #[Test]
    public function the_page_is_unreachable_when_the_feature_is_off(): void
    {
        $panel = Panel::make()->id('shut')->path('shut')->plugin(new MiaTheme);

        Filament::setCurrentPanel($panel);

        $this->assertFalse(PageBuilder::canAccess());
    }

    #[Test]
    public function the_page_is_reachable_once_the_feature_is_on(): void
    {
        $panel = Panel::make()->id('open')->path('open')->plugin((new MiaTheme)->pageBuilder());

        Filament::setCurrentPanel($panel);

        $this->assertTrue(PageBuilder::canAccess());
    }

    #[Test]
    public function the_navigation_placement_is_the_panels_to_decide(): void
    {
        $theme = (new MiaTheme)
            ->pageBuilder()
            ->pageBuilderNavigation(group: 'Site', sort: 20, icon: 'heroicon-o-newspaper');

        $this->assertSame('Site', $theme->getPageBuilderNavigationGroup());
        $this->assertSame(20, $theme->getPageBuilderNavigationSort());
        $this->assertSame('heroicon-o-newspaper', $theme->getPageBuilderNavigationIcon());
    }

    #[Test]
    public function the_navigation_is_left_to_filament_when_nothing_is_said(): void
    {
        $theme = (new MiaTheme)->pageBuilder();

        $this->assertNull($theme->getPageBuilderNavigationGroup());
        $this->assertNull($theme->getPageBuilderNavigationSort());
        $this->assertNull($theme->getPageBuilderNavigationIcon());
    }

    #[Test]
    public function switching_the_feature_back_off_leaves_nothing_behind(): void
    {
        $theme = (new MiaTheme)->pageBuilder()->pageBuilder(false);

        $panel = Panel::make()->id('again')->path('again');
        $theme->register($panel);

        $this->assertFalse($theme->hasPageBuilder());
        $this->assertNotContains(PageBuilder::class, $panel->getPages());
    }
}
