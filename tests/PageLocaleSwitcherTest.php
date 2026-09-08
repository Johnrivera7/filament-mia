<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\Facades\Filament;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Support\LocaleStore;
use JohnRivera7\FilamentMia\Support\PageLocales;
use JohnRivera7\FilamentMia\Tests\Fixtures\LocalePagePanelProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * The language switcher on the public page.
 *
 * A panel of its own — the builder on *and* two languages configured — because
 * that is the only arrangement in which the control has anything to offer, and
 * the assertions about its silence elsewhere have to stay honest.
 */
class PageLocaleSwitcherTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), LocalePagePanelProvider::class];
    }

    /**
     * The rendered page without the stylesheet, which is inlined and mentions
     * every class name whether or not anything uses it.
     *
     * @param  array<string, mixed>  $data
     */
    protected function bar(array $data = []): string
    {
        $html = view('filament-mia::page-builder.layout', [
            'blocks' => [['type' => 'navigation', 'data' => ['visible' => true, ...$data]]],
            'draft' => false,
        ])->render();

        return (string) preg_replace(
            ['#<style\b[^>]*>.*?</style>#s', '#<script\b[^>]*>.*?</script>#s'],
            '',
            $html,
        );
    }

    protected function plugin(): MiaTheme
    {
        /** @var MiaTheme $plugin */
        $plugin = Filament::getPanel('bilingual')->getPlugin('mia-theme');

        return $plugin;
    }

    #[Test]
    public function the_public_page_reads_its_languages_from_the_panel_that_owns_the_builder(): void
    {
        $this->assertSame(['en' => 'English', 'es' => 'Español'], PageLocales::available());
    }

    #[Test]
    public function every_language_is_offered_under_its_own_name(): void
    {
        $html = $this->bar();

        $this->assertStringContainsString('mia-page-locale-panel', $html);
        $this->assertStringContainsString('English', $html);
        $this->assertStringContainsString('Español', $html);
    }

    #[Test]
    public function each_name_links_at_the_switch_route_of_the_panel_it_came_from(): void
    {
        $html = $this->bar();

        foreach (['en', 'es'] as $code) {
            $this->assertStringContainsString(
                route('filament-mia.locale', ['panel' => 'bilingual', 'locale' => $code]),
                $html,
            );
        }
    }

    /**
     * Marked rather than dropped: a list that removes the current entry
     * changes length as it is used, and the visitor loses the one label they
     * could be sure of.
     */
    #[Test]
    public function the_language_already_on_screen_is_marked_as_the_current_one(): void
    {
        app()->setLocale('es');

        $html = $this->bar();

        $this->assertMatchesRegularExpression('/aria-current="true"\s*>Español/', $html);
        $this->assertDoesNotMatchRegularExpression('/aria-current="true"\s*>English/', $html);
    }

    #[Test]
    public function the_switcher_is_left_out_when_the_bar_was_told_not_to_offer_it(): void
    {
        $this->assertStringNotContainsString(
            'mia-page-locale-panel',
            $this->bar(['locale_switch' => false]),
        );
    }

    /**
     * The theme refuses to configure a switcher with one language, so "a
     * single language" is the same state as the switcher being off — and in
     * both the bar draws nothing rather than offering a choice of one.
     */
    #[Test]
    public function nothing_is_drawn_when_the_panel_offers_a_single_language(): void
    {
        $this->plugin()->localeSwitcher(false);

        $html = $this->bar();

        $this->assertSame([], PageLocales::available());
        $this->assertStringNotContainsString('mia-page-locale-panel', $html);

        // The light/dark switch beside it is untouched, so this is the
        // language control going quiet and not the whole bar.
        $this->assertStringContainsString('data-mia-scheme-toggle', $html);
    }

    #[Test]
    public function a_visitor_who_is_not_signed_in_can_choose_a_language(): void
    {
        // No credentials, no panel session: the choice is a cookie on the
        // browser, which is what lets the control work on a public page.
        $this->get(route('filament-mia.locale', ['panel' => 'bilingual', 'locale' => 'es']), ['referer' => url('/')])
            ->assertRedirect(url('/'))
            ->assertCookie(LocaleStore::NAME, 'es');
    }

    #[Test]
    public function the_public_page_comes_back_in_the_language_that_was_chosen(): void
    {
        $this->withCookie(LocaleStore::NAME, 'es')
            ->get('/')
            ->assertOk()
            ->assertSee('lang="es"', escape: false)
            ->assertSee('Saltar al contenido');
    }

    /**
     * The cookie is shared by every panel in the application, so a value
     * another panel offers must not be applied to this page.
     */
    #[Test]
    public function a_language_the_panel_does_not_offer_is_ignored(): void
    {
        $this->withCookie(LocaleStore::NAME, 'fr')
            ->get('/')
            ->assertOk()
            ->assertSee('lang="en"', escape: false);
    }

    #[Test]
    public function the_switcher_is_worded_in_both_languages_the_theme_ships(): void
    {
        $keys = [
            'filament-mia::page-builder.page.language',
            'filament-mia::page-builder.blocks.navigation.locale_switch',
            'filament-mia::page-builder.blocks.navigation.locale_switch_help',
        ];

        foreach (['en', 'es'] as $locale) {
            app()->setLocale($locale);

            foreach ($keys as $key) {
                $this->assertNotSame($key, __($key), "[{$key}] is missing from the {$locale} file.");
            }
        }
    }
}
