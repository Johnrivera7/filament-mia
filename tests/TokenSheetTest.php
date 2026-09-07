<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Support\TokenSheet;

class TokenSheetTest extends TestCase
{
    private function sheet(
        string $panelId = 'admin',
        Roundness $roundness = Roundness::Soft,
        Density $density = Density::Comfortable,
        bool $serifHeadings = true,
        bool $motion = true,
        float $elevation = 1.0,
    ): string {
        return (new TokenSheet(
            $panelId,
            $roundness,
            $density,
            $serifHeadings,
            $motion,
            $elevation,
        ))->render();
    }

    /**
     * Scoping to the panel's own body class, rather than to `:root`, is what
     * allows two panels in one application to be configured differently.
     */
    public function test_declarations_are_scoped_to_the_panel(): void
    {
        $css = $this->sheet(panelId: 'admin');

        $this->assertStringContainsString('.fi-panel-admin{', $css);
        $this->assertStringContainsString('.dark .fi-panel-admin{', $css);
        $this->assertStringNotContainsString(':root', $css);
    }

    /**
     * The panel id reaches the stylesheet as part of a selector, so anything
     * that is not a plain identifier has to be stripped rather than trusted.
     */
    public function test_a_panel_id_cannot_break_out_of_the_selector(): void
    {
        $css = $this->sheet(panelId: 'admin{}</style><script>');

        $this->assertStringNotContainsString('<script', $css);
        $this->assertStringNotContainsString('</style><', $css);
        $this->assertStringContainsString('.fi-panel-adminstylescript{', $css);
    }

    public function test_both_colour_modes_are_emitted(): void
    {
        $css = $this->sheet();

        $this->assertSame(1, substr_count($css, '<style'));
        $this->assertSame(1, substr_count($css, '</style>'));
        $this->assertStringContainsString('--mia-canvas', $css);
    }

    /**
     * Overriding Tailwind's own `--radius-*` is what retunes the `rounded-*`
     * utilities already compiled into the stylesheet, so roundness is
     * configurable without a rebuild.
     */
    public function test_roundness_retunes_tailwinds_radius_scale(): void
    {
        $sharp = $this->sheet(roundness: Roundness::Sharp);
        $round = $this->sheet(roundness: Roundness::Round);

        $this->assertStringContainsString('--radius-lg:', $sharp);
        $this->assertStringContainsString('--radius-lg:', $round);
        $this->assertNotSame($sharp, $round);
    }

    public function test_density_changes_the_scale_and_row_height(): void
    {
        $compact = $this->sheet(density: Density::Compact);
        $spacious = $this->sheet(density: Density::Spacious);

        $this->assertStringContainsString('--mia-density:', $compact);
        $this->assertStringContainsString('--mia-row-height:', $compact);
        $this->assertNotSame($compact, $spacious);
    }

    /**
     * Disabling motion must not disable it by setting a zero duration, which
     * would stop `transitionend` firing and leave Alpine transitions unresolved.
     */
    public function test_disabling_motion_keeps_a_non_zero_duration(): void
    {
        $css = $this->sheet(motion: false);

        $this->assertStringContainsString('--default-transition-duration:', $css);
        $this->assertStringNotContainsString('--default-transition-duration:0s', $css);
        $this->assertStringNotContainsString('--default-transition-duration:0ms', $css);
    }

    public function test_zero_elevation_flattens_every_shadow(): void
    {
        $css = $this->sheet(elevation: 0.0);

        $this->assertStringContainsString('--mia-shadow-sm:none', $css);
        $this->assertStringContainsString('--mia-shadow-xl:none', $css);
    }

    public function test_serif_headings_can_be_turned_off(): void
    {
        $with = $this->sheet(serifHeadings: true);
        $without = $this->sheet(serifHeadings: false);

        $this->assertStringContainsString('--mia-heading-font', $with);
        $this->assertNotSame($with, $without);
    }
}
