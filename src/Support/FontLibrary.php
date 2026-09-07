<?php

namespace JohnRivera7\FilamentMia\Support;

/**
 * The families offered by the customiser.
 *
 * Deliberately a short list rather than the whole of Bunny Fonts. The theme is
 * built around a particular pairing — a geometric humanist sans against a
 * high-contrast display serif — and most families do not hold up at the sizes
 * and letter-spacing the stylesheet applies to headings and tracked caps. Each
 * family here has been checked to render on the panel's own headings, column
 * headings and stat captions.
 *
 * These lists constrain the *customiser* only. `->font()` accepts any family
 * Bunny Fonts serves, so nothing here limits what can be set from code.
 */
class FontLibrary
{
    /**
     * Interface families, for content and controls.
     *
     * @return array<string, string>
     */
    public static function sans(): array
    {
        return [
            'Jost' => 'Jost — geometric, the theme default',
            'Outfit' => 'Outfit — geometric, slightly wider',
            'Manrope' => 'Manrope — semi-grotesque, quiet',
            'Nunito Sans' => 'Nunito Sans — humanist, softer terminals',
            'DM Sans' => 'DM Sans — low contrast, compact',
            'Figtree' => 'Figtree — humanist, generous x-height',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans — geometric, open',
            'Urbanist' => 'Urbanist — geometric, low contrast',
            'Karla' => 'Karla — grotesque, slightly quirky',
            'Work Sans' => 'Work Sans — humanist, sturdy',
        ];
    }

    /**
     * Display families, for headings and brand type.
     *
     * @return array<string, string>
     */
    public static function serif(): array
    {
        return [
            'Cormorant Garamond' => 'Cormorant Garamond — high contrast, the theme default',
            'Playfair Display' => 'Playfair Display — high contrast, editorial',
            'Fraunces' => 'Fraunces — soft serif, characterful',
            'EB Garamond' => 'EB Garamond — old style, warm',
            'Lora' => 'Lora — moderate contrast, sturdy',
            'Crimson Pro' => 'Crimson Pro — old style, book-like',
            'Bodoni Moda' => 'Bodoni Moda — very high contrast, formal',
            'DM Serif Display' => 'DM Serif Display — high contrast, compact',
            'Spectral' => 'Spectral — low contrast, screen-first',
            'Newsreader' => 'Newsreader — editorial, open counters',
        ];
    }

    /**
     * @return array<string>
     */
    public static function sansFamilies(): array
    {
        return array_keys(static::sans());
    }

    /**
     * @return array<string>
     */
    public static function serifFamilies(): array
    {
        return array_keys(static::serif());
    }
}
