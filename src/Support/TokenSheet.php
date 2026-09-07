<?php

namespace JohnRivera7\FilamentMia\Support;

use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;

/**
 * Renders the theme's runtime custom properties.
 *
 * Emitted through `PanelsRenderHook::STYLES_AFTER`, i.e. after the theme's own
 * stylesheet, so these declarations win on source order alone.
 *
 * Two of the properties written here are Tailwind's, not the theme's:
 * `--radius-*` and `--default-transition-*`. Overriding them retunes every
 * `rounded-*` and `transition` utility already compiled into the stylesheet,
 * which is what makes roundness and motion configurable without a rebuild.
 *
 * Everything is scoped to `.fi-panel-{id}` — the class Filament puts on
 * `<body>` — rather than `:root`, so two panels can be configured differently.
 * The declarations and the tokens they reference therefore resolve on the same
 * element, which `:root` would not allow.
 */
class TokenSheet
{
    public function __construct(
        protected string $panelId,
        protected Roundness $roundness,
        protected Density $density,
        protected bool $serifHeadings,
        protected bool $motion,
        protected float $elevation,
    ) {}

    public function render(): string
    {
        $selector = '.fi-panel-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $this->panelId);

        return sprintf(
            '<style id="mia-theme-tokens">%s{%s}.dark %s{%s}</style>',
            $selector,
            $this->declarations($this->lightTokens()),
            $selector,
            $this->declarations($this->darkTokens()),
        );
    }

    /** @return array<string, string> */
    protected function lightTokens(): array
    {
        $duration = $this->motion ? '260ms' : '1ms';

        return [
            ...$this->radiusTokens(),
            ...$this->densityTokens(),

            // Warm every surface that the framework paints pure white, without
            // untethering it from the configured neutral.
            'color-white' => 'color-mix(in oklab, var(--gray-50) 55%, white)',

            'default-transition-duration' => $duration,
            'default-transition-timing-function' => 'var(--mia-ease)',

            // A long, gentle ease-out. Movement decelerates for most of its
            // duration, which reads as unhurried rather than snappy.
            'mia-ease' => 'cubic-bezier(0.22, 0.61, 0.24, 1)',
            'mia-duration' => $duration,
            'mia-duration-slow' => $this->motion ? '420ms' : '1ms',
            'mia-anim-duration' => $this->motion ? '520ms' : '1ms',

            'mia-canvas' => 'var(--gray-50)',
            'mia-surface' => 'var(--color-white)',
            'mia-surface-sunken' => 'color-mix(in oklab, var(--gray-100) 70%, var(--color-white))',
            'mia-surface-raised' => 'var(--color-white)',
            'mia-hairline' => 'color-mix(in oklab, var(--gray-300) 55%, transparent)',
            'mia-hairline-strong' => 'color-mix(in oklab, var(--gray-400) 55%, transparent)',
            'mia-ink' => 'var(--gray-950)',
            'mia-ink-muted' => 'var(--gray-500)',
            'mia-accent-wash' => 'color-mix(in oklab, var(--primary-200) 42%, transparent)',
            'mia-accent-line' => 'color-mix(in oklab, var(--primary-500) 55%, transparent)',

            'mia-heading-font' => $this->serifHeadings ? 'var(--font-serif)' : 'var(--font-sans)',
            'mia-heading-weight' => $this->serifHeadings ? '500' : '600',
            'mia-heading-tracking' => $this->serifHeadings ? '-0.011em' : '-0.018em',

            ...$this->shadowTokens(
                ambient: 0.05,
                cast: 0.09,
                deep: 0.11,
            ),
        ];
    }

    /**
     * Dark mode is not a recolour of light mode: the surfaces climb the warm
     * neutral instead of descending it, and shadows lean on depth of tone
     * rather than opacity, which pure-black shadows cannot provide here.
     *
     * @return array<string, string>
     */
    protected function darkTokens(): array
    {
        return [
            'mia-canvas' => 'var(--gray-950)',
            'mia-surface' => 'var(--gray-900)',
            'mia-surface-sunken' => 'color-mix(in oklab, var(--gray-950) 60%, var(--gray-900))',
            'mia-surface-raised' => 'var(--gray-800)',
            'mia-hairline' => 'color-mix(in oklab, var(--gray-700) 62%, transparent)',
            'mia-hairline-strong' => 'color-mix(in oklab, var(--gray-600) 70%, transparent)',
            'mia-ink' => 'var(--color-white)',
            'mia-ink-muted' => 'var(--gray-400)',
            'mia-accent-wash' => 'color-mix(in oklab, var(--primary-500) 16%, transparent)',
            'mia-accent-line' => 'color-mix(in oklab, var(--primary-400) 45%, transparent)',

            ...$this->shadowTokens(
                ambient: 0.30,
                cast: 0.42,
                deep: 0.52,
            ),
        ];
    }

    /** @return array<string, string> */
    protected function radiusTokens(): array
    {
        $tokens = [];

        foreach ($this->roundness->tokens() as $name => $value) {
            $tokens["mia-{$name}"] = $value;
        }

        // Retune Tailwind's own scale so the stylesheet's existing `rounded-*`
        // utilities follow the configured roundness.
        foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $step) {
            $tokens["radius-{$step}"] = "var(--mia-radius-{$step})";
        }

        return $tokens;
    }

    /** @return array<string, string> */
    protected function densityTokens(): array
    {
        $tokens = [];

        foreach ($this->density->tokens() as $name => $value) {
            $tokens["mia-{$name}"] = $value;
        }

        return $tokens;
    }

    /**
     * Diffuse, wide shadows tinted with the darkest neutral. Blur radii are
     * large and offsets small, so elevation reads as ambient light rather than
     * a hard drop shadow.
     *
     * @return array<string, string>
     */
    protected function shadowTokens(float $ambient, float $cast, float $deep): array
    {
        $tint = fn (float $alpha): string => sprintf(
            'color-mix(in oklab, var(--gray-950) %s%%, transparent)',
            round(min(100, $alpha * $this->elevation * 100), 2),
        );

        return [
            'mia-shadow-sm' => sprintf(
                '0 1px 2px %s',
                $tint($ambient * 0.8),
            ),
            'mia-shadow-md' => sprintf(
                '0 1px 2px %s, 0 8px 20px -6px %s',
                $tint($ambient * 0.7),
                $tint($cast),
            ),
            'mia-shadow-lg' => sprintf(
                '0 1px 3px %s, 0 18px 40px -12px %s',
                $tint($ambient * 0.7),
                $tint($cast * 1.1),
            ),
            'mia-shadow-xl' => sprintf(
                '0 2px 4px %s, 0 32px 72px -20px %s',
                $tint($ambient * 0.8),
                $tint($deep),
            ),
        ];
    }

    /** @param  array<string, string>  $tokens */
    protected function declarations(array $tokens): string
    {
        $css = '';

        foreach ($tokens as $name => $value) {
            $css .= "--{$name}:{$value};";
        }

        return $css;
    }
}
