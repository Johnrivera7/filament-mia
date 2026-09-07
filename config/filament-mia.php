<?php

/*
|--------------------------------------------------------------------------
| Mía — a warm editorial theme for Filament
|--------------------------------------------------------------------------
|
| These values are the defaults for every panel running the theme. Anything
| set fluently on the plugin takes precedence, so this file is the place for
| project-wide defaults and the fluent API is the place for per-panel
| exceptions:
|
|     ->plugin(MiaTheme::make()->accentColor('#C9A227'))
|
| Colours are resolved at runtime and emitted as OKLCH custom properties, so
| changing anything here takes effect immediately — the stylesheet never needs
| to be recompiled.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Colours
    |--------------------------------------------------------------------------
    |
    | Each value seeds an eleven-shade ramp. The theme preserves the hue *and*
    | the saturation character of what it is given, so an understated colour
    | stays understated rather than being pushed to full chroma.
    |
    | Accepted formats: hex (#RGB or #RRGGBB), rgb(r, g, b), a bare "r, g, b"
    | triplet, or oklch(l c h). Invalid values fail loudly at boot.
    |
    */

    'colors' => [

        // The accent that carries buttons, links, focus rings and active states.
        // A soft honey gold, drawn from the brand illustration.
        'accent' => '#D9A14E',

        // A supporting colour, available to components as ->color('secondary').
        // A desaturated dusty rose.
        'secondary' => '#E4A987',

        // Page backgrounds, surfaces, borders and body copy are all built from
        // the neutral. Leave null to keep the curated warm ramp, which is tuned
        // so that light mode reads as cream and dark mode as espresso.
        'neutral' => null,

        // Status colours, warmed to sit inside the palette instead of cutting
        // across it with stock blues and greens.
        'danger' => '#C1614F',
        'info' => '#8B9FB0',
        'success' => '#8A9A6B',
        'warning' => '#D9A441',

    ],

    /*
    |--------------------------------------------------------------------------
    | Typography
    |--------------------------------------------------------------------------
    |
    | Families are served from Bunny Fonts, a privacy-friendly mirror that sets
    | no cookies and logs no IP addresses, so the theme adds no third-party
    | tracking and needs no cookie notice.
    |
    | Give plain family names, without quotes or CSS fallbacks.
    |
    */

    'fonts' => [

        // The interface sans, used for content and controls.
        'sans' => 'Jost',

        // The display serif, used for page headings and brand type.
        'serif' => 'Cormorant Garamond',

        // Optional monospace family. Null keeps the system stack.
        'mono' => null,

    ],

    'typography' => [

        // Set headings in the serif family. Disable for a quieter interface
        // that stays in the sans throughout.
        'serif_headings' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Shape and spacing
    |--------------------------------------------------------------------------
    |
    | Both are applied as custom properties over Tailwind's own scales, so they
    | retune the whole interface at runtime.
    |
    | roundness: sharp | subtle | soft | round
    | density:   compact | comfortable | spacious
    |
    */

    'roundness' => 'soft',

    'density' => 'comfortable',

    // Narrower than Filament's 20rem default, which crowds the content column
    // on smaller laptops. Any CSS length.
    'sidebar_width' => '17rem',

    /*
    |--------------------------------------------------------------------------
    | Depth
    |--------------------------------------------------------------------------
    |
    | Multiplier for the shadow system. Shadows are wide, diffuse and tinted
    | with the neutral rather than pure black at any value. Use 0 for a
    | completely flat interface.
    |
    */

    'elevation' => 1.0,

    /*
    |--------------------------------------------------------------------------
    | Motion
    |--------------------------------------------------------------------------
    |
    | Entry animations and hover micro-interactions. This is independent of
    | `prefers-reduced-motion`, which the theme always honours.
    |
    */

    'motion' => true,

    /*
    |--------------------------------------------------------------------------
    | Sign-in
    |--------------------------------------------------------------------------
    |
    | The sign-in screen is the only part of a panel a visitor sees without an
    | account, so the theme offers five compositions of it rather than one.
    | They differ in staging, not in identity: what changes is where the form
    | sits and what occupies the rest of the viewport.
    |
    |   card       A single card centred on the canvas, over two soft pools of
    |              warm light. The quietest of the five.
    |   split      Two columns, one of them brand territory in a deep warm
    |              field. Folds to a banner above the form on narrow screens.
    |   bleed      A warm field running to every edge, with the form on
    |              frosted glass above it.
    |   editorial  Asymmetric and print-like: the form anchored to one side
    |              with no card around it, the facing side left as air.
    |   portal     A narrow, tall column with a brand medallion above the
    |              heading and no card edge at all.
    |
    | The choice applies to the whole flow — sign-in, registration, password
    | reset and the multi-factor challenge — so a panel does not change shape
    | halfway through logging in.
    |
    */

    'login' => [

        'layout' => 'card',

        // A single line of copy for the brand stage. Only `split` and
        // `editorial` have somewhere to put it; the others ignore it.
        'tagline' => null,

    ],

    /*
    |--------------------------------------------------------------------------
    | Dark mode
    |--------------------------------------------------------------------------
    |
    | Whether the panel offers the light/dark switch. The dark palette is built
    | from the same neutral ramp as the light one, so both modes stay coherent.
    |
    */

    'dark_mode' => true,

    /*
    |--------------------------------------------------------------------------
    | Appearance page
    |--------------------------------------------------------------------------
    |
    | Adds a page to the panel where the theme can be edited from the interface
    | and the result saved. Off by default: it changes how the panel looks for
    | everyone who uses it, which should be a deliberate choice rather than
    | something that appears in the navigation on install.
    |
    | Restrict who can open it with ->customizerAuthorization(), which takes a
    | closure and has no config equivalent:
    |
    |     ->plugin(
    |         MiaTheme::make()
    |             ->customizer()
    |             ->customizerAuthorization(fn (): bool => auth()->user()?->isAdmin())
    |     )
    |
    | Saved settings take precedence over this file and over the fluent API,
    | because they are the most recent deliberate decision. The page's reset
    | action discards the saved record and returns the panel to your code.
    |
    */

    'customizer' => [

        'enabled' => false,

        // Navigation group for the page. Null leaves it ungrouped.
        'navigation_group' => null,

        'navigation_sort' => null,

    ],

    /*
    |--------------------------------------------------------------------------
    | Application stylesheets
    |--------------------------------------------------------------------------
    |
    | A pre-compiled theme can only contain the utility classes Filament itself
    | uses — it cannot know about classes in your own Blade views, because
    | those files do not exist when the theme is built.
    |
    | If you write Tailwind utilities in your own views, compile them yourself
    | and list the Vite entrypoints here. They are loaded after the theme,
    | alongside it rather than instead of it. Do not use Panel::viteTheme() for
    | this: Filament gives it unconditional precedence over theme() and would
    | replace this theme entirely.
    |
    |     'vite_stylesheets' => ['resources/css/filament/admin/utilities.css'],
    |
    */

    'vite_stylesheets' => [],

    'vite_build_directory' => null,

];
