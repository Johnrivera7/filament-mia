<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;
use JohnRivera7\FilamentMia\Pages\PageBuilder;
use JohnRivera7\FilamentMia\Tests\Fixtures\PageBuilderPanelProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * The other half of {@see PageBuilderTest}: what appears once a panel does
 * switch the builder on.
 *
 * A panel of its own, registered only for this class, so the assertions about
 * silence in the other tests stay honest — a route registered globally by a
 * fixture would make them pass for the wrong reason.
 */
class PageBuilderRoutesTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), PageBuilderPanelProvider::class];
    }

    #[Test]
    public function the_builder_is_registered_on_the_panel_that_asked_for_it(): void
    {
        $this->assertContains(PageBuilder::class, Filament::getPanel('builder')->getPages());
    }

    #[Test]
    public function the_panel_that_asked_for_it_is_the_one_that_answers(): void
    {
        $this->assertSame('builder', PageBuilderPanel::resolve()?->getId());
    }

    #[Test]
    public function the_public_page_and_its_draft_both_get_an_address(): void
    {
        $this->assertTrue(Route::has('filament-mia.page'));
        $this->assertTrue(Route::has('filament-mia.page.preview'));
    }

    #[Test]
    public function the_public_page_answers_at_the_site_root_by_default(): void
    {
        $this->assertSame('/', Route::getRoutes()->getByName('filament-mia.page')?->uri());
    }

    #[Test]
    public function the_published_page_is_served_to_a_visitor_who_is_not_signed_in(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('mia-page', escape: false);
    }

    /**
     * An empty page rather than a 500. The migration is published, not
     * shipped, so a table that is not there yet is a normal state on the way
     * to switching the feature on — and the site's front door is the worst
     * place to raise an exception about it.
     */
    #[Test]
    public function a_missing_table_reads_as_a_page_with_nothing_on_it(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(__('filament-mia::page-builder.page.empty_heading'));
    }

    #[Test]
    public function the_draft_asks_a_visitor_who_is_not_signed_in_for_credentials(): void
    {
        $this->get(route('filament-mia.page.preview'))
            ->assertRedirect(Filament::getPanel('builder')->getLoginUrl());
    }

    #[Test]
    public function the_way_in_is_the_panels_own_sign_in_screen(): void
    {
        $panel = Filament::getPanel('builder');

        $this->assertSame($panel->getLoginUrl(), PageBuilderPanel::signInUrl($panel));
    }
}
