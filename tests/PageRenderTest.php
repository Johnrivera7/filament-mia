<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\PageBuilder\DefaultContent;
use PHPUnit\Framework\Attributes\Test;

/**
 * The public page, rendered.
 *
 * The view is rendered directly rather than reached over HTTP, because the
 * route only exists once a panel switches the builder on and what is being
 * checked here is the markup, not the wiring — that is
 * {@see PageBuilderTest}'s job.
 */
class PageRenderTest extends TestCase
{
    /**
     * @param  list<array{type: string, data: array<string, mixed>}>  $blocks
     */
    protected function render(array $blocks, bool $draft = false): string
    {
        return view('filament-mia::page-builder.layout', [
            'blocks' => $blocks,
            'draft' => $draft,
        ])->render();
    }

    /**
     * The rendered page without the stylesheet and the scripts it carries.
     *
     * Both are inlined, so a class name is in the document whether or not
     * anything uses it — which would quietly turn every "this did not render"
     * assertion below into one that can never fail.
     *
     * @param  list<array{type: string, data: array<string, mixed>}>  $blocks
     */
    protected function body(array $blocks, bool $draft = false): string
    {
        $html = $this->render($blocks, $draft);

        return (string) preg_replace(
            ['#<style\b[^>]*>.*?</style>#s', '#<script\b[^>]*>.*?</script>#s'],
            '',
            $html,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{type: string, data: array<string, mixed>}
     */
    protected function block(string $type, array $data = []): array
    {
        return ['type' => $type, 'data' => ['visible' => true, ...$data]];
    }

    #[Test]
    public function the_starter_page_renders_every_section_it_ships_with(): void
    {
        $blocks = array_values(array_filter(
            DefaultContent::blocks(),
            fn (array $block): bool => $block['data']['visible'] ?? true,
        ));

        $html = $this->render($blocks);

        foreach (['navigation', 'hero', 'metrics', 'features', 'steps', 'faq', 'call_to_action', 'footer'] as $type) {
            $this->assertStringNotContainsString(
                "page-builder.blocks.{$type}",
                $html,
                "The {$type} partial did not render.",
            );
        }

        $this->assertStringContainsString('mia-page-display', $html);
        $this->assertStringContainsString('mia-page-eyebrow', $html);
        $this->assertStringContainsString('mia-page-faq-item', $html);
    }

    #[Test]
    public function every_block_in_the_catalogue_has_a_partial_that_renders(): void
    {
        // A block offered in the picker with no partial behind it renders as
        // nothing at all, silently, which is the failure this catches.
        $types = [
            'navigation', 'hero', 'features', 'steps', 'comparison', 'metrics',
            'testimonials', 'pricing', 'faq', 'call_to_action', 'footer',
        ];

        foreach ($types as $type) {
            $html = $this->render([$this->block($type)]);

            $this->assertStringContainsString('mia-page', $html, "The {$type} block did not render.");
        }
    }

    #[Test]
    public function a_surface_becomes_the_class_that_re_points_the_palette(): void
    {
        $html = $this->render([
            $this->block('features', ['surface' => 'deep', 'heading' => 'Deep']),
        ]);

        $this->assertStringContainsString('mia-page-section--deep', $html);
    }

    #[Test]
    public function an_unknown_surface_falls_back_to_the_canvas(): void
    {
        $html = $this->render([
            $this->block('features', ['surface' => 'chartreuse', 'heading' => 'Fallback']),
        ]);

        $this->assertStringNotContainsString('mia-page-section--chartreuse', $html);
        $this->assertStringContainsString('mia-page-section', $html);
    }

    #[Test]
    public function an_anchor_becomes_an_id_other_blocks_can_link_to(): void
    {
        $html = $this->render([
            $this->block('features', ['anchor' => 'how-it-works', 'heading' => 'Anchored']),
        ]);

        $this->assertStringContainsString('id="how-it-works"', $html);
    }

    #[Test]
    public function the_main_landmark_opens_on_the_first_block_that_is_not_the_bar(): void
    {
        $html = $this->render([
            $this->block('navigation'),
            $this->block('hero', ['heading' => 'First']),
            $this->block('features', ['heading' => 'Second']),
        ]);

        // Exactly once, or a screen reader is offered two places to skip to.
        $this->assertSame(1, substr_count($html, 'id="mia-page-content"'));
    }

    #[Test]
    public function a_page_with_no_navigation_still_has_somewhere_to_skip_to(): void
    {
        $html = $this->render([$this->block('hero', ['heading' => 'Alone'])]);

        $this->assertSame(1, substr_count($html, 'id="mia-page-content"'));
    }

    #[Test]
    public function an_empty_page_says_what_to_do_about_it(): void
    {
        $html = $this->render([]);

        $this->assertStringContainsString(__('filament-mia::page-builder.page.empty_heading'), $html);
        $this->assertStringContainsString(__('filament-mia::page-builder.page.empty_body'), $html);
    }

    #[Test]
    public function the_draft_says_it_is_a_draft_and_asks_not_to_be_indexed(): void
    {
        $html = $this->render([$this->block('hero', ['heading' => 'Unfinished'])], draft: true);

        $this->assertStringContainsString('noindex, nofollow', $html);
        $this->assertStringContainsString(__('filament-mia::page-builder.page.draft_notice'), $html);
    }

    #[Test]
    public function the_published_page_is_left_for_search_engines_to_index(): void
    {
        $html = $this->body([$this->block('hero', ['heading' => 'Live'])]);

        $this->assertStringNotContainsString('noindex', $html);
        $this->assertStringNotContainsString(__('filament-mia::page-builder.page.draft_notice'), $html);
    }

    #[Test]
    public function the_light_dark_switch_is_only_offered_when_it_was_asked_for(): void
    {
        $with = $this->body([$this->block('navigation', ['scheme_toggle' => true])]);
        $without = $this->body([$this->block('navigation', ['scheme_toggle' => false])]);

        $this->assertStringContainsString('data-mia-scheme-toggle', $with);
        $this->assertStringNotContainsString('data-mia-scheme-toggle', $without);
    }

    /**
     * A control that only works with JavaScript is hidden until the script
     * that gives it meaning has run, so nobody is shown a dead button.
     */
    #[Test]
    public function the_switch_starts_hidden(): void
    {
        $html = $this->body([$this->block('navigation', ['scheme_toggle' => true])]);

        $this->assertStringContainsString('data-mia-scheme-toggle hidden', $html);
    }

    /**
     * The bar asks for the switcher by default, so the silence has to come
     * from there being nothing to switch between. {@see PageLocaleSwitcherTest}
     * covers the panel that does offer languages.
     */
    #[Test]
    public function the_language_switcher_stays_away_until_a_panel_offers_languages(): void
    {
        $html = $this->body([$this->block('navigation', ['locale_switch' => true])]);

        $this->assertStringNotContainsString('mia-page-locale-panel', $html);
    }

    #[Test]
    public function the_bar_sticks_only_when_it_was_told_to(): void
    {
        $sticky = $this->body([$this->block('navigation', ['sticky' => true])]);
        $loose = $this->body([$this->block('navigation', ['sticky' => false])]);

        $this->assertStringContainsString('mia-page-nav--sticky', $sticky);
        $this->assertStringNotContainsString('mia-page-nav--sticky', $loose);
    }

    #[Test]
    public function the_brand_falls_back_to_the_application_name(): void
    {
        config()->set('app.name', 'Atelier');

        $html = $this->render([$this->block('navigation')]);

        $this->assertStringContainsString('Atelier', $html);
    }

    #[Test]
    public function an_action_carries_the_style_it_was_given(): void
    {
        $html = $this->render([
            $this->block('call_to_action', [
                'heading' => 'Start',
                'actions' => [
                    ['label' => 'Primary', 'url' => '#a', 'style' => 'solid'],
                    ['label' => 'Secondary', 'url' => '#b', 'style' => 'outline'],
                    ['label' => 'Quiet', 'url' => '#c', 'style' => 'text'],
                ],
            ]),
        ]);

        $this->assertStringContainsString('mia-page-btn--solid', $html);
        $this->assertStringContainsString('mia-page-btn--outline', $html);
        $this->assertStringContainsString('mia-page-btn--text', $html);
    }

    #[Test]
    public function an_action_missing_its_text_or_its_destination_is_dropped(): void
    {
        $html = $this->body([
            $this->block('call_to_action', [
                'heading' => 'Start',
                'actions' => [
                    ['label' => 'Nowhere', 'url' => null],
                    ['label' => null, 'url' => '#somewhere'],
                    ['label' => 'Good', 'url' => '#good'],
                ],
            ]),
        ]);

        $this->assertStringNotContainsString('Nowhere', $html);
        $this->assertStringNotContainsString('#somewhere', $html);
        $this->assertStringContainsString('#good', $html);
    }

    #[Test]
    public function an_off_site_link_is_told_not_to_hand_over_the_window(): void
    {
        $html = $this->render([
            $this->block('call_to_action', [
                'heading' => 'Away',
                'actions' => [['label' => 'Elsewhere', 'url' => 'https://example.test/x']],
            ]),
        ]);

        $this->assertStringContainsString('rel="noopener"', $html);
    }

    #[Test]
    public function a_repeater_entry_with_nothing_in_it_is_left_out(): void
    {
        $html = $this->body([
            $this->block('features', [
                'heading' => 'Features',
                'items' => [
                    ['title' => 'Real', 'text' => 'Something'],
                    ['title' => null, 'text' => 'Orphaned text'],
                ],
            ]),
        ]);

        $this->assertStringContainsString('Real', $html);
        $this->assertStringNotContainsString('Orphaned text', $html);
    }

    #[Test]
    public function an_icon_outside_the_curated_list_is_not_rendered(): void
    {
        $html = $this->body([
            $this->block('features', [
                'heading' => 'Features',
                'items' => [['title' => 'One', 'icon' => 'heroicon-o-rocket-launch']],
            ]),
        ]);

        // An arbitrary icon name from stored content would otherwise reach
        // Blade Icons, which throws when the set does not have it.
        $this->assertStringNotContainsString('mia-page-feature-icon', $html);
    }

    #[Test]
    public function a_section_that_would_render_empty_renders_nothing(): void
    {
        foreach (['testimonials', 'pricing', 'faq', 'metrics', 'comparison'] as $type) {
            $html = $this->body([$this->block($type, ['heading' => 'Heading'])]);

            $this->assertStringNotContainsString('Heading', $html, "The empty {$type} section printed its heading.");
        }
    }

    #[Test]
    public function pricing_turns_one_line_per_benefit_into_a_list(): void
    {
        $html = $this->body([
            $this->block('pricing', [
                'heading' => 'Plans',
                'items' => [[
                    'name' => 'Studio',
                    'price' => '49',
                    'features' => "First point\nSecond point\n\n  Third point  ",
                ]],
            ]),
        ]);

        $this->assertSame(3, substr_count($html, 'mia-page-tick'));
        $this->assertStringContainsString('Third point', $html);
    }

    #[Test]
    public function the_stylesheet_travels_with_the_page(): void
    {
        $html = $this->render([$this->block('hero', ['heading' => 'Styled'])]);

        // No panel around this page, so nothing else would emit the tokens
        // the rules read.
        $this->assertStringContainsString('--mia-page-canvas', $html);
        $this->assertStringContainsString('.mia-page-section', $html);
        $this->assertStringContainsString('fonts.bunny.net', $html);
    }

    #[Test]
    public function the_page_is_marked_up_in_the_language_the_panel_is_in(): void
    {
        app()->setLocale('es');

        $html = $this->render([]);

        $this->assertStringContainsString('lang="es"', $html);
        $this->assertStringContainsString('Saltar al contenido', $html);
    }
}
