<?php

namespace JohnRivera7\FilamentMia\Support;

/**
 * The complete stylesheet for a page built with the page builder.
 *
 * Same problem as an error page, same answer: there is no panel around a
 * public page, so Filament emits none of its OKLCH ramps and the plugin
 * injects none of its `--mia-*` tokens. {@see StandaloneSheet} already builds
 * all of that from `config/filament-mia.php` and hands back a self-contained
 * `<style>` body; this subclass adds the slots the page's own rules read and
 * swaps in `resources/css/page.css`.
 *
 * Reading from the config file rather than from the settings the appearance
 * page saves is inherited, and is the right behaviour here too: the public
 * page is the part of the site least likely to want to follow whatever an
 * administrator last tried out in the panel.
 *
 * ---------------------------------------------------------------------------
 * Contrast
 * ---------------------------------------------------------------------------
 *
 * Every pairing below is one the theme already measures somewhere else rather
 * than a new one invented for the page:
 *
 *  - Body copy is `--gray-600` on cream and `--gray-400` on espresso, which is
 *    the floor `TokenSheet` settled on for the panel; the shade above it
 *    measures around 4.1:1 and does not pass AA.
 *  - Accent *text* uses the 700 shade in light and the 300 in dark. The accent
 *    itself — the 500/600 band — is a mid tone by construction and cannot
 *    carry small text on either canvas.
 *  - The solid button is white on the 600 shade in light and the darkest
 *    neutral on the 400 shade in dark, the pairing `StandaloneSheet` uses for
 *    the same control.
 *  - On the espresso band the accent moves to the 300 shade and the solid
 *    button to the 400 with dark ink, because the band keeps its own ink in
 *    both modes rather than inverting with the page.
 */
class PageSheet extends StandaloneSheet
{
    /**
     * The hand-written rules for the public page.
     */
    protected function rules(): string
    {
        $css = @file_get_contents(__DIR__ . '/../../resources/css/page.css');

        if ($css === false) {
            return '';
        }

        $css = preg_replace('#/\*.*?\*/#s', '', $css) ?? $css;

        return trim(preg_replace('/\n\s*\n/', "\n", $css) ?? $css);
    }

    /**
     * @return array<string, string>
     */
    protected function lightTokens(): array
    {
        return [
            ...parent::lightTokens(),

            'mia-page-canvas' => 'var(--gray-50)',
            'mia-page-canvas-warm' => 'color-mix(in oklab, var(--gray-100) 82%, var(--primary-100))',
            'mia-page-surface' => 'var(--color-white)',
            'mia-page-surface-raised' => 'var(--color-white)',
            'mia-page-surface-sunken' => 'color-mix(in oklab, var(--gray-100) 70%, var(--color-white))',

            'mia-page-ink' => 'var(--gray-950)',
            'mia-page-ink-muted' => 'var(--gray-600)',

            'mia-page-hairline' => 'color-mix(in oklab, var(--gray-300) 55%, transparent)',
            'mia-page-hairline-strong' => 'color-mix(in oklab, var(--gray-400) 55%, transparent)',

            // Borders that carry meaning on their own, so they answer to
            // WCAG 1.4.11 rather than to the decorative hairline.
            'mia-page-control-border' => 'color-mix(in oklab, var(--gray-500) 85%, transparent)',

            'mia-page-accent' => 'var(--primary-700)',
            'mia-page-accent-strong' => 'var(--primary-800)',
            'mia-page-accent-face' => 'var(--primary-600)',
            'mia-page-accent-face-hover' => 'var(--primary-700)',
            'mia-page-accent-ink' => 'white',
            'mia-page-accent-wash' => 'color-mix(in oklab, var(--primary-200) 42%, transparent)',
            'mia-page-accent-line' => 'color-mix(in oklab, var(--primary-500) 55%, transparent)',
            'mia-page-secondary-wash' => 'color-mix(in oklab, var(--secondary-200) 45%, transparent)',

            ...$this->deepTokens(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function darkTokens(): array
    {
        return [
            ...parent::darkTokens(),

            'mia-page-canvas' => 'var(--gray-950)',
            'mia-page-canvas-warm' => 'var(--gray-900)',
            'mia-page-surface' => 'var(--gray-900)',
            'mia-page-surface-raised' => 'var(--gray-900)',
            'mia-page-surface-sunken' => 'color-mix(in oklab, var(--gray-950) 60%, var(--gray-900))',

            'mia-page-ink' => 'var(--color-white)',
            'mia-page-ink-muted' => 'var(--gray-400)',

            'mia-page-hairline' => 'color-mix(in oklab, var(--gray-700) 62%, transparent)',
            'mia-page-hairline-strong' => 'color-mix(in oklab, var(--gray-600) 70%, transparent)',
            'mia-page-control-border' => 'color-mix(in oklab, var(--gray-500) 90%, transparent)',

            'mia-page-accent' => 'var(--primary-300)',
            'mia-page-accent-strong' => 'var(--primary-200)',
            'mia-page-accent-face' => 'var(--primary-400)',
            'mia-page-accent-face-hover' => 'var(--primary-300)',
            'mia-page-accent-ink' => 'var(--gray-950)',
            'mia-page-accent-wash' => 'color-mix(in oklab, var(--primary-500) 16%, transparent)',
            'mia-page-accent-line' => 'color-mix(in oklab, var(--primary-400) 45%, transparent)',
            'mia-page-secondary-wash' => 'color-mix(in oklab, var(--secondary-500) 14%, transparent)',

            // The espresso band is already dark, so it reads the same in both
            // modes apart from the two surfaces that would otherwise sit
            // lighter than the page around them.
            ...$this->deepTokens(),
            'mia-page-deep' => 'var(--gray-900)',
            'mia-page-deep-alt' => 'var(--gray-950)',
            'mia-page-deep-surface' => 'var(--gray-800)',
        ];
    }

    /**
     * The espresso band: a dark register a cream page can be punctuated with,
     * carrying its own ink so it survives the page going dark around it.
     *
     * @return array<string, string>
     */
    protected function deepTokens(): array
    {
        return [
            'mia-page-deep' => 'var(--gray-900)',
            'mia-page-deep-alt' => 'var(--gray-950)',
            'mia-page-deep-surface' => 'color-mix(in oklab, var(--gray-800) 82%, var(--gray-900))',
            'mia-page-deep-ink' => 'var(--gray-50)',
            'mia-page-deep-ink-muted' => 'var(--gray-300)',
            'mia-page-deep-hairline' => 'color-mix(in oklab, var(--gray-600) 45%, transparent)',
            'mia-page-deep-control-border' => 'color-mix(in oklab, var(--gray-400) 75%, transparent)',
            'mia-page-deep-accent' => 'var(--primary-300)',
            'mia-page-deep-accent-face' => 'var(--primary-400)',
            'mia-page-deep-accent-face-hover' => 'var(--primary-300)',
            'mia-page-deep-accent-ink' => 'var(--gray-950)',
            'mia-page-deep-accent-wash' => 'color-mix(in oklab, var(--primary-400) 18%, transparent)',
        ];
    }
}
