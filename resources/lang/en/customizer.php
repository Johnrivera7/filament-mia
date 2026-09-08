<?php

return [

    'title' => 'Appearance',

    'subheading' => 'Adjust the theme and see the result as you go. Changes affect this panel for everyone; nothing is stored until you save.',

    'mode' => [
        'label' => 'Preview in',
        'light' => 'Light',
        'dark' => 'Dark',
        'system' => 'System',
        'disabled' => 'This panel is set to a single colour mode.',
    ],

    'presets' => [
        'heading' => 'Presets',
        'description' => 'A complete starting point. Applying one fills the form below, which you can then adjust.',
        'items' => [
            'mia' => [
                'label' => 'Mía',
                'description' => 'Honey gold on cream, set in Cormorant Garamond. The theme as shipped.',
            ],
            'atelier' => [
                'label' => 'Atelier',
                'description' => 'Terracotta and clay, tighter corners, a soft serif for headings.',
            ],
            'botanica' => [
                'label' => 'Botanica',
                'description' => 'Sage and wheat, rounder and more spacious, with an old-style serif.',
            ],
            'papier' => [
                'label' => 'Papier',
                'description' => 'Flat and printed: no shadows, crisp corners, compact rows, Playfair headings.',
            ],
            'plain' => [
                'label' => 'Plain',
                'description' => 'The warm palette and shapes, but the whole interface in the sans. No serif.',
            ],
        ],
    ],

    'colour' => [
        'heading' => 'Colour',
        'description' => 'Each colour seeds an eleven-shade ramp that keeps its hue and how saturated it is, so an understated colour stays understated.',
        'accent' => 'Accent',
        'accent_help' => 'Buttons, links, focus rings and active navigation.',
        'secondary' => 'Secondary',
        'secondary_help' => 'A supporting colour, used where a component asks for it.',
        'custom_neutral' => 'Replace the neutral',
        'custom_neutral_help' => 'Backgrounds, surfaces, borders and body copy are built from the neutral. The shipped ramp is a warm taupe tuned for cream in light mode and espresso in dark; replace it only if you want a different temperature throughout.',
        'neutral' => 'Neutral',
        'neutral_help' => 'Give a mid tone. The ramp is derived from it.',
        'danger' => 'Danger',
        'warning' => 'Warning',
        'success' => 'Success',
        'info' => 'Info',
    ],

    'type' => [
        'heading' => 'Typography',
        'description' => 'Served from Bunny Fonts, which sets no cookies and logs no IP addresses. The list is short on purpose: these are the families that hold up at the sizes and letter-spacing the theme uses.',
        'sans' => 'Interface',
        'serif' => 'Headings',
        'serif_headings' => 'Set headings in the serif',
        'serif_headings_help' => 'Turn this off to keep the whole interface in the interface family, for a quieter look.',
    ],

    'shape' => [
        'heading' => 'Shape and density',
        'description' => 'Corner radii and row heights, applied over the whole interface at once.',
        'roundness' => 'Roundness',
        'roundness_options' => [
            'sharp' => 'Sharp',
            'subtle' => 'Subtle',
            'soft' => 'Soft',
            'round' => 'Round',
        ],
        'density' => 'Density',
        'density_options' => [
            'compact' => 'Compact',
            'comfortable' => 'Comfortable',
            'spacious' => 'Spacious',
        ],
    ],

    'depth' => [
        'heading' => 'Depth and motion',
        'description' => 'How much the interface lifts off the page, and how it moves.',
        'elevation' => 'Elevation',
        'elevation_help' => 'Scales every shadow. 0 is completely flat. Shadows are tinted with the neutral rather than black at any value.',
        'motion' => 'Motion',
        'motion_help' => 'Entry animations and hover transitions. Independent of the reader\'s reduced-motion setting, which is always honoured.',
    ],

    'login' => [
        'heading' => 'Sign-in screen',
        'description' => 'The only part of this panel a visitor sees without an account. Five compositions are available; they differ in where the form sits and what fills the rest of the screen, not in palette or type.',
        'layout' => 'Composition',
        'options' => [
            'card' => 'Centred card',
            'split' => 'Split stage',
            'bleed' => 'Full bleed',
            'editorial' => 'Editorial',
            'portal' => 'Portal',
        ],
        'descriptions' => [
            'card' => 'A single card centred on the canvas, over two soft pools of warm light. The quietest of the five.',
            'split' => 'Two columns, one of them brand territory in a deep warm field. On narrow screens it folds to a banner above the form.',
            'bleed' => 'A warm field running to every edge, with the form on frosted glass above it.',
            'editorial' => 'Asymmetric and print-like: the form anchored to one side with no card around it, the facing side left as air.',
            'portal' => 'A narrow, tall column with a brand medallion above the heading and no card edge at all.',
        ],
        'tagline' => 'Line of copy',
        'tagline_help' => 'Shown on the brand side, under the panel name. One line; long values are trimmed. Only these two compositions have room for it.',
        'tagline_placeholder' => 'A line of copy about your product.',
        'sample_heading' => 'Sign in',
        'sample_email' => 'Email address',
        'sample_password' => 'Password',
        'sample_action' => 'Sign in',
    ],

    'specimen' => [
        'heading' => 'Specimen',
        'description' => 'The components the settings affect most.',
        'sample_heading' => 'A heading, set in the display family',
        'sample_body' => 'Body copy in the interface family, at the size and measure the theme uses for content. Long enough to judge the pairing, the colour of the text against the page, and how much air the density setting leaves around it.',
        'primary_action' => 'Primary action',
        'secondary_action' => 'Secondary',
        'badge_success' => 'Settled',
        'badge_warning' => 'Pending',
        'badge_danger' => 'Overdue',
        'badge_info' => 'Draft',
    ],

    'actions' => [
        'save' => 'Save',
        'reset' => 'Reset',
        'reset_heading' => 'Reset the appearance?',
        'reset_description' => 'The saved settings are discarded and the panel returns to the values in your configuration file and code. This affects everyone using this panel.',
    ],

    'notifications' => [
        'saved' => 'Appearance saved',
        'reset' => 'Appearance reset',
        'invalid' => 'Those settings could not be saved',
    ],

];
