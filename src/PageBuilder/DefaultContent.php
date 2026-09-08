<?php

namespace JohnRivera7\FilamentMia\PageBuilder;

/**
 * The page a fresh install starts from.
 *
 * Copy comes from `resources/lang/{locale}/page-builder.php` rather than being
 * written here, for the same reason every other visible string in the theme
 * does: a starter page in a language the person editing does not read is a
 * starter page they have to delete rather than edit.
 *
 * It describes a product in the abstract — a section per shape the catalogue
 * offers, with placeholder copy that says what the section is for. Two blocks
 * are deliberately left out of it: testimonials and pricing. Inventing a quote
 * nobody said or a price nobody charges is the one kind of placeholder that is
 * worse than an empty section, and both remain available to add.
 */
final class DefaultContent
{
    /** @return array<string, mixed> */
    public static function payload(): array
    {
        return ['blocks' => self::blocks()];
    }

    /** @return list<array{type: string, data: array<string, mixed>}> */
    public static function blocks(): array
    {
        return [
            self::block('navigation', [
                'brand' => config('app.name'),
                'sticky' => true,
                'scheme_toggle' => true,
                'links' => [
                    ['label' => self::text('nav.features'), 'url' => '#features'],
                    ['label' => self::text('nav.steps'), 'url' => '#how-it-works'],
                    ['label' => self::text('nav.faq'), 'url' => '#faq'],
                ],
                'actions' => [
                    ['label' => self::text('nav.action'), 'url' => '#start', 'style' => 'outline'],
                ],
            ]),

            self::block('hero', [
                'anchor' => 'top',
                'surface' => 'warm',
                'eyebrow' => self::text('hero.eyebrow'),
                'heading' => self::text('hero.heading'),
                'heading_accent' => self::text('hero.heading_accent'),
                'lead' => self::text('hero.lead'),
                'actions' => [
                    ['label' => self::text('hero.primary'), 'url' => '#start', 'style' => 'solid'],
                    ['label' => self::text('hero.secondary'), 'url' => '#how-it-works', 'style' => 'outline'],
                ],
                'image_alt' => '',
                'card_visible' => true,
                'card_eyebrow' => self::text('hero.card_eyebrow'),
                'card_note' => self::text('hero.card_note'),
                'card_title' => self::text('hero.card_title'),
                'card_subtitle' => self::text('hero.card_subtitle'),
                'card_rows' => [
                    [
                        'label' => self::text('hero.rows.one.label'),
                        'title' => self::text('hero.rows.one.title'),
                        'note' => self::text('hero.rows.one.note'),
                    ],
                    [
                        'label' => self::text('hero.rows.two.label'),
                        'title' => self::text('hero.rows.two.title'),
                        'note' => self::text('hero.rows.two.note'),
                    ],
                    [
                        'label' => self::text('hero.rows.three.label'),
                        'title' => self::text('hero.rows.three.title'),
                        'note' => self::text('hero.rows.three.note'),
                    ],
                ],
            ]),

            self::block('metrics', [
                'surface' => 'raised',
                'eyebrow' => self::text('metrics.eyebrow'),
                'items' => [
                    ['value' => self::text('metrics.one.value'), 'label' => self::text('metrics.one.label')],
                    ['value' => self::text('metrics.two.value'), 'label' => self::text('metrics.two.label')],
                    ['value' => self::text('metrics.three.value'), 'label' => self::text('metrics.three.label')],
                ],
            ]),

            self::block('features', [
                'anchor' => 'features',
                'surface' => 'canvas',
                'eyebrow' => self::text('features.eyebrow'),
                'heading' => self::text('features.heading'),
                'heading_quiet' => self::text('features.heading_quiet'),
                'lead' => self::text('features.lead'),
                'columns' => 3,
                'items' => [
                    [
                        'title' => self::text('features.one.title'),
                        'icon' => 'heroicon-o-sparkles',
                        'text' => self::text('features.one.text'),
                    ],
                    [
                        'title' => self::text('features.two.title'),
                        'icon' => 'heroicon-o-bolt',
                        'text' => self::text('features.two.text'),
                    ],
                    [
                        'title' => self::text('features.three.title'),
                        'icon' => 'heroicon-o-shield-check',
                        'text' => self::text('features.three.text'),
                    ],
                    [
                        'title' => self::text('features.four.title'),
                        'icon' => 'heroicon-o-user-group',
                        'text' => self::text('features.four.text'),
                    ],
                    [
                        'title' => self::text('features.five.title'),
                        'icon' => 'heroicon-o-arrow-path',
                        'text' => self::text('features.five.text'),
                    ],
                    [
                        'title' => self::text('features.six.title'),
                        'icon' => 'heroicon-o-code-bracket',
                        'text' => self::text('features.six.text'),
                    ],
                ],
            ]),

            self::block('steps', [
                'anchor' => 'how-it-works',
                'surface' => 'deep',
                'eyebrow' => self::text('steps.eyebrow'),
                'heading' => self::text('steps.heading'),
                'lead' => self::text('steps.lead'),
                'items' => [
                    ['title' => self::text('steps.one.title'), 'text' => self::text('steps.one.text')],
                    ['title' => self::text('steps.two.title'), 'text' => self::text('steps.two.text')],
                    ['title' => self::text('steps.three.title'), 'text' => self::text('steps.three.text')],
                ],
            ]),

            self::block('faq', [
                'anchor' => 'faq',
                'surface' => 'warm',
                'eyebrow' => self::text('faq.eyebrow'),
                'heading' => self::text('faq.heading'),
                'items' => [
                    ['question' => self::text('faq.one.question'), 'answer' => self::text('faq.one.answer')],
                    ['question' => self::text('faq.two.question'), 'answer' => self::text('faq.two.answer')],
                    ['question' => self::text('faq.three.question'), 'answer' => self::text('faq.three.answer')],
                ],
            ]),

            self::block('call_to_action', [
                'anchor' => 'start',
                'surface' => 'deep',
                'eyebrow' => self::text('call_to_action.eyebrow'),
                'heading' => self::text('call_to_action.heading'),
                'lead' => self::text('call_to_action.lead'),
                'actions' => [
                    ['label' => self::text('call_to_action.primary'), 'url' => '#top', 'style' => 'solid'],
                ],
            ]),

            self::block('footer', [
                'surface' => 'deep',
                'brand' => config('app.name'),
                'description' => self::text('footer.description'),
                'links' => [
                    ['label' => self::text('nav.features'), 'url' => '#features'],
                    ['label' => self::text('nav.steps'), 'url' => '#how-it-works'],
                    ['label' => self::text('nav.faq'), 'url' => '#faq'],
                ],
                'legal' => self::text('footer.legal', ['year' => date('Y'), 'name' => config('app.name')]),
            ]),
        ];
    }

    /** @param  array<string, mixed>  $replace */
    private static function text(string $key, array $replace = []): string
    {
        return __("filament-mia::page-builder.starter.{$key}", $replace);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{type: string, data: array<string, mixed>}
     */
    private static function block(string $type, array $data): array
    {
        return [
            'type' => $type,
            'data' => ['visible' => true, 'anchor' => null, ...$data],
        ];
    }
}
