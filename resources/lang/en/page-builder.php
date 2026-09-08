<?php

return [

    'title' => 'Public page',

    'navigation' => 'Public page',

    'subheading' => 'Add sections, drag them to reorder, and edit their content. Everything is saved as a draft until you publish.',

    'sections' => 'Sections',

    'add_section' => 'Add a section',

    'unpublished_hint' => 'There are changes visitors cannot see yet.',

    'actions' => [
        'save' => 'Save draft',
        'publish' => 'Publish changes',
        'unpublished' => 'unpublished',
        'open_draft' => 'Open the draft',
        'reset' => 'Restore the starter page',
        'reset_description' => 'The draft’s sections are replaced with the ones the theme ships. Nothing changes for visitors until you publish again.',
        'reset_confirm' => 'Restore',
    ],

    'notifications' => [
        'saved' => 'Draft saved',
        'saved_body' => 'Publish whenever you want visitors to see it.',
        'published' => 'Page published',
        'published_body' => 'Visitors are now seeing this version.',
    ],

    'preview' => [
        'heading' => 'Preview',
        'description' => 'Shows the draft. Visitors still see whatever you published last.',
        'refresh' => 'Refresh',
        'live' => 'Live',
        'live_hint' => 'Leaving a field saves the draft and refreshes the preview.',
        'rendered_at' => 'Rendered at :width px and scaled to fit.',
        'widths' => [
            'mobile' => 'Mobile',
            'tablet' => 'Tablet',
            'desktop' => 'Desktop',
        ],
    ],

    'page' => [
        'skip' => 'Skip to content',
        'sections' => 'Sections',
        'menu' => 'Open the menu',
        'footer_links' => 'Footer links',
        'draft_notice' => 'You are looking at the draft. Visitors still see the published version.',
        'empty_heading' => 'This page has no content yet',
        'empty_body' => 'Add and publish sections from the page builder and they will appear here.',
        'switch_to_light' => 'Switch to light mode',
        'switch_to_dark' => 'Switch to dark mode',
        'language' => 'Change the language',
    ],

    'surfaces' => [
        'canvas' => 'Canvas — the base background',
        'warm' => 'Warm — a step warmer, for alternating',
        'raised' => 'Raised — the light card surface',
        'deep' => 'Deep — a dark band with light type',
    ],

    'shared' => [
        'heading' => 'Section settings',
        'visible' => 'Visible on the page',
        'visible_help' => 'Switching this off hides the section but keeps its content.',
        'anchor' => 'Anchor',
        'anchor_help' => 'An identifier to link to from the menu, without the “#”. For example: how-it-works.',
        'anchor_format' => 'Use lowercase letters, numbers and hyphens only.',
        'surface' => 'Background',
        'actions' => 'Buttons',
        'action_label' => 'Text',
        'action_url' => 'Destination',
        'action_url_help' => 'A full URL or an anchor such as #pricing.',
        'action_style' => 'Style',
        'styles' => [
            'solid' => 'Solid — the main action',
            'outline' => 'Outline — a secondary action',
            'text' => 'Text only',
        ],
        'links' => 'Links',
        'eyebrow' => 'Eyebrow',
        'eyebrow_help' => 'The short line in small capitals above the heading.',
        'heading_text' => 'Heading',
        'heading_quiet' => 'Continuation of the heading, in a quieter tone',
        'heading_quiet_help' => 'Printed after the heading as part of the same line.',
        'lead' => 'Lead',
        'title' => 'Title',
        'subtitle' => 'Subtitle',
        'description' => 'Description',
        'icon' => 'Icon',
    ],

    'blocks' => [

        'navigation' => [
            'label' => 'Navigation bar',
            'brand' => 'Name',
            'brand_help' => 'Leave empty to use the application name.',
            'logo' => 'Logo',
            'logo_help' => 'Optional. Shown before the name.',
            'sticky' => 'Stick to the top when scrolling',
            'scheme_toggle' => 'Offer a light/dark switch',
            'scheme_toggle_help' => 'Follows the visitor’s system preference until they choose.',
            'locale_switch' => 'Offer a language switcher',
            'locale_switch_help' => 'Only appears when the panel offers more than one language.',
            'actions' => 'Bar action',
        ],

        'hero' => [
            'label' => 'Hero',
            'heading' => 'Headline',
            'heading_accent' => 'End of the headline, in the accent colour',
            'heading_accent_help' => 'Printed after the headline. Leave empty to highlight nothing.',
            'image' => 'Image',
            'image_help' => 'Optional. Shown beside the headline. 2 MB maximum.',
            'image_alt' => 'Alternative text for the image',
            'image_alt_help' => 'Describe it for anyone who cannot see it. Leave empty only if it is purely decorative.',
            'card' => 'Sample card',
            'card_visible' => 'Show the card',
            'card_eyebrow' => 'Eyebrow',
            'card_note' => 'Note',
            'card_title' => 'Title',
            'card_subtitle' => 'Subtitle',
            'card_rows' => 'Rows',
            'card_row_label' => 'Label',
            'card_row_title' => 'Text',
            'card_row_note' => 'Detail',
        ],

        'features' => [
            'label' => 'Features',
            'columns' => 'Columns on desktop',
            'two' => 'Two',
            'three' => 'Three',
            'items' => 'Features',
        ],

        'steps' => [
            'label' => 'How it works',
            'items' => 'Steps',
            'title' => 'Step title',
        ],

        'comparison' => [
            'label' => 'Comparison',
            'columns' => 'Column headings',
            'primary_title' => 'Highlighted column',
            'secondary_title' => 'Contrasting column',
            'items' => 'Rows',
            'criterion' => 'Criterion',
            'primary_value' => 'Highlighted value',
            'secondary_value' => 'Contrasting value',
        ],

        'metrics' => [
            'label' => 'Figures',
            'items' => 'Figures',
            'value' => 'Figure',
            'label_field' => 'What it measures',
        ],

        'testimonials' => [
            'label' => 'Testimonials',
            'items' => 'Testimonials',
            'quote' => 'Quote',
            'author' => 'Who said it',
            'role' => 'Role or organisation',
            'avatar' => 'Portrait',
        ],

        'pricing' => [
            'label' => 'Pricing',
            'items' => 'Plans',
            'name' => 'Plan name',
            'featured' => 'Highlight this plan',
            'price' => 'Price',
            'price_help' => 'Write it exactly as it should read, for example: 49 a month, or On request.',
            'period' => 'Period',
            'description' => 'Who it is for',
            'features' => 'What it includes',
            'features_help' => 'One line per point.',
            'action_label' => 'Button text',
            'action_url' => 'Button destination',
            'note' => 'Note under the section',
        ],

        'faq' => [
            'label' => 'Questions',
            'items' => 'Questions',
            'question' => 'Question',
            'answer' => 'Answer',
        ],

        'call_to_action' => [
            'label' => 'Call to action',
        ],

        'footer' => [
            'label' => 'Footer',
            'description' => 'Short description',
            'legal' => 'Legal note',
        ],

    ],

    /*
     * The page a fresh install starts from. Placeholder copy that says what
     * each section is for, rather than invented claims about a product the
     * theme knows nothing about.
     */
    'starter' => [

        'nav' => [
            'features' => 'Features',
            'steps' => 'How it works',
            'faq' => 'Questions',
            'action' => 'Get in touch',
        ],

        'hero' => [
            'eyebrow' => 'Replace this line',
            'heading' => 'A headline that says what you do,',
            'heading_accent' => 'in one breath.',
            'lead' => 'A sentence or two under the headline, for the part the headline had to leave out. Keep it to what a first-time visitor needs before deciding to read on.',
            'primary' => 'Primary action',
            'secondary' => 'How it works',
            'card_eyebrow' => 'Sample',
            'card_note' => 'Editable',
            'card_title' => 'A sample of the product',
            'card_subtitle' => 'Markup rather than a screenshot, so it stays legible at any width',
            'rows' => [
                'one' => [
                    'label' => 'Row',
                    'title' => 'Something the product shows',
                    'note' => 'With a detail beside it',
                ],
                'two' => [
                    'label' => 'Row',
                    'title' => 'Another one, to give the card weight',
                    'note' => 'Up to four in total',
                ],
                'three' => [
                    'label' => 'Row',
                    'title' => 'Switch the card off if you would rather not',
                    'note' => 'Or replace it with an image',
                ],
            ],
        ],

        'metrics' => [
            'eyebrow' => 'In numbers',
            'one' => ['value' => '3', 'label' => 'figures fit comfortably on one line'],
            'two' => ['value' => '4', 'label' => 'is the most this section will show'],
            'three' => ['value' => '1', 'label' => 'claim per figure, and make it one you can stand behind'],
        ],

        'features' => [
            'eyebrow' => 'What it does',
            'heading' => 'The things worth saying,',
            'heading_quiet' => 'and nothing else.',
            'lead' => 'Two or three columns of short entries. An icon each is optional, and a section of six reads better than a section of twelve.',
            'one' => ['title' => 'The first thing', 'text' => 'A sentence or two on what it is and why it matters. Concrete beats impressive.'],
            'two' => ['title' => 'The second thing', 'text' => 'Say what happens, not what it enables. A reader can work out the rest.'],
            'three' => ['title' => 'The third thing', 'text' => 'If a feature needs a paragraph, it probably wants a section of its own.'],
            'four' => ['title' => 'The fourth thing', 'text' => 'Entries can be reordered by dragging them, and removed when they stop being true.'],
            'five' => ['title' => 'The fifth thing', 'text' => 'The icons come from a short curated list, so a page cannot end up with twelve unrelated styles.'],
            'six' => ['title' => 'The sixth thing', 'text' => 'Six is a full grid at three columns. Stop here unless you have a reason not to.'],
        ],

        'steps' => [
            'eyebrow' => 'How it works',
            'heading' => 'Three steps, in order.',
            'lead' => 'The order is the meaning, so this section numbers itself.',
            'one' => ['title' => 'The first step', 'text' => 'What the reader does to begin. Written as an instruction rather than a description.'],
            'two' => ['title' => 'The second step', 'text' => 'What happens next, and who does it — them or you.'],
            'three' => ['title' => 'The third step', 'text' => 'What they end up with. This is the step worth being specific about.'],
        ],

        'faq' => [
            'eyebrow' => 'Questions',
            'heading' => 'What people ask before they start.',
            'one' => [
                'question' => 'What belongs in this section?',
                'answer' => 'The questions that come up before someone commits, answered plainly. If an answer is embarrassing to write, it is usually the most useful one on the page.',
            ],
            'two' => [
                'question' => 'How many questions should there be?',
                'answer' => 'As many as are genuinely asked. Three real questions are worth more than ten written to fill the section.',
            ],
            'three' => [
                'question' => 'Can this section be removed?',
                'answer' => 'Yes. Every section can be hidden without losing its content, or deleted outright, and added again from the picker.',
            ],
        ],

        'call_to_action' => [
            'eyebrow' => 'To begin',
            'heading' => 'One clear next step',
            'lead' => 'End the page with the single thing you want the reader to do, and only that thing.',
            'primary' => 'Get started',
        ],

        'footer' => [
            'description' => 'A line about what this is, for anyone who arrived at the bottom of the page first.',
            'legal' => '© :year :name. All rights reserved.',
        ],

    ],

];
