<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\Support\PageSheet;
use PHPUnit\Framework\Attributes\Test;

/**
 * The stylesheet the public page carries with it.
 *
 * There is no panel around that page, so nothing else emits the colour ramps,
 * the font families or the `--mia-*` tokens the rules read. If any of that is
 * missing the page renders as unstyled text, which is why it is asserted here
 * rather than left to the eye.
 */
class PageSheetTest extends TestCase
{
    protected function css(): string
    {
        return PageSheet::fromConfig()->css();
    }

    #[Test]
    public function the_sheet_carries_both_the_tokens_and_the_rules(): void
    {
        $css = $this->css();

        $this->assertStringContainsString('--mia-page-canvas:', $css);
        $this->assertStringContainsString('.mia-page-section', $css);
        $this->assertStringContainsString('.mia-page-btn', $css);
    }

    #[Test]
    public function the_colour_ramps_are_emitted_as_literal_values(): void
    {
        $css = $this->css();

        // Filament emits these from inside a panel render; a public page has
        // to be given them.
        $this->assertStringContainsString('--primary-600:', $css);
        $this->assertStringContainsString('--gray-950:', $css);
        $this->assertStringContainsString('--secondary-200:', $css);
    }

    #[Test]
    public function the_font_families_travel_with_the_page(): void
    {
        $css = $this->css();

        $this->assertStringContainsString('--font-family:', $css);
        $this->assertStringContainsString('--serif-font-family:', $css);
        $this->assertStringContainsString('jost', PageSheet::fromConfig()->fontUrl());
    }

    /**
     * Three selectors, because a public page resolves its mode three ways at
     * once: a stored preference, the operating system's, or neither. The
     * media query is qualified so a visitor who chose light keeps light on a
     * dark desktop.
     */
    #[Test]
    public function dark_mode_is_emitted_for_both_a_stored_choice_and_the_system(): void
    {
        $css = $this->css();

        $this->assertStringContainsString(":root[data-mia-scheme='dark']", $css);
        $this->assertStringContainsString('@media (prefers-color-scheme: dark)', $css);
        $this->assertStringContainsString(":root:not([data-mia-scheme='light'])", $css);
    }

    #[Test]
    public function the_dark_declarations_come_after_the_light_ones(): void
    {
        $css = $this->css();

        // Identical specificity, so source order settles them.
        $this->assertLessThan(
            strpos($css, "[data-mia-scheme='dark']"),
            strpos($css, ':root{color-scheme:light'),
        );
    }

    #[Test]
    public function the_dark_mode_slots_are_overridden_rather_than_inherited(): void
    {
        $css = $this->css();
        $dark = substr($css, (int) strpos($css, "[data-mia-scheme='dark']"));

        foreach (['--mia-page-canvas:', '--mia-page-ink:', '--mia-page-accent:', '--mia-page-accent-ink:'] as $token) {
            $this->assertStringContainsString($token, $dark, "Dark mode never re-points {$token}.");
        }
    }

    /**
     * The dark band a cream page is punctuated with keeps its own ink, so it
     * survives the page going dark around it rather than inverting into
     * something illegible.
     */
    #[Test]
    public function the_deep_band_carries_its_own_ink_and_accent(): void
    {
        $css = $this->css();

        $this->assertStringContainsString('--mia-page-deep-ink:', $css);
        $this->assertStringContainsString('--mia-page-deep-accent:', $css);
        $this->assertStringContainsString('.mia-page-section--deep', $css);
    }

    /**
     * Accent *text* has to sit at one end of the ramp — the 500/600 band the
     * accent itself occupies is a mid tone by construction and cannot carry
     * small text on either canvas.
     */
    #[Test]
    public function accent_text_uses_the_dark_end_of_the_ramp_in_light_mode(): void
    {
        $css = $this->css();
        $light = substr($css, 0, (int) strpos($css, "[data-mia-scheme='dark']"));

        $this->assertStringContainsString('--mia-page-accent:var(--primary-700)', $light);
        $this->assertStringContainsString('--mia-page-accent-ink:white', $light);
        $this->assertStringContainsString('--mia-page-accent-face:var(--primary-600)', $light);
    }

    #[Test]
    public function accent_text_moves_to_the_light_end_of_the_ramp_in_dark_mode(): void
    {
        $css = $this->css();
        $dark = substr($css, (int) strpos($css, "[data-mia-scheme='dark']"));

        $this->assertStringContainsString('--mia-page-accent:var(--primary-300)', $dark);
        $this->assertStringContainsString('--mia-page-accent-ink:var(--gray-950)', $dark);
        $this->assertStringContainsString('--mia-page-accent-face:var(--primary-400)', $dark);
    }

    /**
     * Body copy sits on the shade the panel already settled on as its floor:
     * the one above it measures around 4.1:1 and does not pass AA.
     */
    #[Test]
    public function body_copy_sits_on_the_shade_the_theme_measured_for_it(): void
    {
        $css = $this->css();
        $light = substr($css, 0, (int) strpos($css, "[data-mia-scheme='dark']"));
        $dark = substr($css, (int) strpos($css, "[data-mia-scheme='dark']"));

        $this->assertStringContainsString('--mia-page-ink-muted:var(--gray-600)', $light);
        $this->assertStringContainsString('--mia-page-ink-muted:var(--gray-400)', $dark);
    }

    /**
     * The rules are the part of the theme most likely to drift, because a
     * literal colour written into them cannot be recoloured by an application
     * and would not follow the accent it configured.
     */
    #[Test]
    public function the_rules_name_no_literal_colours(): void
    {
        $rules = (string) file_get_contents(__DIR__ . '/../resources/css/page.css');
        $rules = (string) preg_replace('#/\*.*?\*/#s', '', $rules);

        $this->assertSame(
            [],
            $this->allMatching('/#[0-9a-fA-F]{3,8}\b/', $rules),
            'A literal hex colour in page.css cannot follow the configured palette.',
        );

        $this->assertSame(
            [],
            $this->allMatching('/\b(?:rgba?|hsla?)\s*\(/', $rules),
            'A literal colour function in page.css cannot follow the configured palette.',
        );
    }

    #[Test]
    public function the_rules_carry_no_tailwind_utilities_of_their_own(): void
    {
        $rules = (string) file_get_contents(__DIR__ . '/../resources/css/page.css');
        $rules = (string) preg_replace('#/\*.*?\*/#s', '', $rules);

        // The file is inlined verbatim, never compiled, so an `@import` or an
        // `@apply` in it would simply be dead text in the page's `<style>`.
        $this->assertStringNotContainsString('@import', $rules);
        $this->assertStringNotContainsString('@apply', $rules);
    }

    #[Test]
    public function the_scheme_script_reads_the_key_filament_itself_writes(): void
    {
        $script = PageSheet::fromConfig()->schemeScript();

        $this->assertStringContainsString("localStorage.getItem('theme')", $script);
        $this->assertStringContainsString('miaScheme', $script);
    }

    /**
     * The page follows `config/filament-mia.php`, like the other pages drawn
     * outside a panel. Recolouring the theme has to recolour the public page
     * with it, or the site and the panel it belongs to drift apart.
     */
    #[Test]
    public function the_configured_accent_reaches_the_page(): void
    {
        $shipped = $this->accentShade();

        config()->set('filament-mia.colors.accent', '#7C8F5F');

        $this->assertNotSame($shipped, $this->accentShade());
    }

    protected function accentShade(): string
    {
        preg_match('/--primary-600:([^;]+);/', PageSheet::fromConfig()->css(), $found);

        return $found[1] ?? '';
    }

    /** @return list<string> */
    protected function allMatching(string $pattern, string $subject): array
    {
        preg_match_all($pattern, $subject, $found);

        return array_values(array_unique($found[0]));
    }
}
