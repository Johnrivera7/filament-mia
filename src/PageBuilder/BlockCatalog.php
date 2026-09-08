<?php

namespace JohnRivera7\FilamentMia\PageBuilder;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;

/**
 * The catalogue of sections a page can be built from.
 *
 * Each entry is a Filament builder block: a name, an icon, and the schema for
 * its own content. The public page renders whatever blocks it finds, in order,
 * delegating each to `filament-mia::page-builder.blocks.{type}` — so adding a
 * section means adding a block here and a partial there, and nothing else.
 *
 * Why a block catalogue rather than a drag-and-drop canvas: a canvas has to
 * own layout, and the moment the person editing can place anything anywhere,
 * every guarantee the theme makes — the type scale, the contrast ratios, the
 * behaviour at 320px — becomes their problem instead of the theme's. A
 * catalogue keeps those guarantees intact while still letting the page be
 * composed, reordered and switched on and off, which is almost all of what a
 * page builder is actually used for.
 *
 * Every block shares three fields, added by {@see self::shared()}: whether it
 * is visible, the anchor other blocks can link to, and which of the theme's
 * surfaces it sits on. Those three are what let the page's rhythm — cream,
 * warm, espresso — be composed from the panel instead of being fixed in the
 * markup.
 */
class BlockCatalog
{
    /**
     * Surfaces a block may sit on.
     *
     * Drawn from the theme's own tokens rather than from arbitrary colours, so
     * no combination can fall outside the palette or below its measured
     * contrast. Each one re-points the semantic slots the partials read, which
     * is why a block does not have to know where it landed.
     */
    public const SURFACES = ['canvas', 'warm', 'raised', 'deep'];

    /**
     * A short, deliberate list. Icons are decorative here, so a handful of
     * recognisable outlines covers most sections without turning the picker
     * into a catalogue of its own. Extend it with {@see self::icons()}.
     *
     * @var list<string>
     */
    protected static array $icons = [
        'heroicon-o-sparkles',
        'heroicon-o-bolt',
        'heroicon-o-shield-check',
        'heroicon-o-user-group',
        'heroicon-o-chat-bubble-left-right',
        'heroicon-o-clock',
        'heroicon-o-arrow-path',
        'heroicon-o-chart-bar',
        'heroicon-o-document-text',
        'heroicon-o-inbox-stack',
        'heroicon-o-globe-alt',
        'heroicon-o-code-bracket',
        'heroicon-o-cube',
        'heroicon-o-heart',
        'heroicon-o-map-pin',
        'heroicon-o-magnifying-glass',
    ];

    /**
     * Replace the icons the picker offers.
     *
     * Any icon set registered with Blade Icons works, so an application that
     * ships its own outlines can put them here. Labels are the icon names
     * themselves, minus the set prefix, because a curated icon list is chosen
     * by eye rather than read.
     *
     * @param  list<string>  $icons
     */
    public static function icons(array $icons): void
    {
        static::$icons = array_values(array_filter($icons, 'is_string'));
    }

    /** @return list<Block> */
    public static function blocks(): array
    {
        return [
            static::navigation(),
            static::hero(),
            static::features(),
            static::steps(),
            static::comparison(),
            static::metrics(),
            static::testimonials(),
            static::pricing(),
            static::faq(),
            static::callToAction(),
            static::footer(),
        ];
    }

    /** @return array<string, string> */
    public static function surfaceOptions(): array
    {
        $options = [];

        foreach (self::SURFACES as $surface) {
            $options[$surface] = __("filament-mia::page-builder.surfaces.{$surface}");
        }

        return $options;
    }

    /** @return array<string, string> */
    public static function iconOptions(): array
    {
        $options = [];

        foreach (static::$icons as $icon) {
            $options[$icon] = (string) preg_replace('/^heroicon-[a-z]+-/', '', $icon);
        }

        return $options;
    }

    public static function isKnownIcon(?string $icon): bool
    {
        return $icon !== null && in_array($icon, static::$icons, true);
    }

    /**
     * The three fields every block carries.
     *
     * `visible` is a soft delete: switching a block off hides it from the
     * public page but keeps its content, so turning it back on restores the
     * section rather than presenting an empty one.
     *
     * @return list<Component>
     */
    protected static function shared(string $defaultSurface = 'canvas', bool $offersSurface = true): array
    {
        $fields = [
            Toggle::make('visible')
                ->label(__('filament-mia::page-builder.shared.visible'))
                ->helperText(__('filament-mia::page-builder.shared.visible_help'))
                ->default(true)
                ->inline(false),
            TextInput::make('anchor')
                ->label(__('filament-mia::page-builder.shared.anchor'))
                ->helperText(__('filament-mia::page-builder.shared.anchor_help'))
                ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ->validationMessages(['regex' => __('filament-mia::page-builder.shared.anchor_format')])
                ->maxLength(60),
        ];

        if ($offersSurface) {
            $fields[] = Select::make('surface')
                ->label(__('filament-mia::page-builder.shared.surface'))
                ->options(static::surfaceOptions())
                ->default($defaultSurface)
                ->selectablePlaceholder(false)
                ->native(false);
        }

        return [
            Fieldset::make(__('filament-mia::page-builder.shared.heading'))
                ->columns(3)
                ->schema($fields),
        ];
    }

    /** Call-to-action links, used by several blocks. */
    protected static function actions(?string $label = null, int $maxItems = 3): Repeater
    {
        return Repeater::make('actions')
            ->label($label ?? __('filament-mia::page-builder.shared.actions'))
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->collapsible()
            ->collapsed()
            ->maxItems($maxItems)
            ->schema([
                Grid::make(3)->schema([
                    TextInput::make('label')
                        ->label(__('filament-mia::page-builder.shared.action_label'))
                        ->required()
                        ->maxLength(48),
                    TextInput::make('url')
                        ->label(__('filament-mia::page-builder.shared.action_url'))
                        ->helperText(__('filament-mia::page-builder.shared.action_url_help'))
                        ->required()
                        ->maxLength(300),
                    Select::make('style')
                        ->label(__('filament-mia::page-builder.shared.action_style'))
                        ->options([
                            'solid' => __('filament-mia::page-builder.shared.styles.solid'),
                            'outline' => __('filament-mia::page-builder.shared.styles.outline'),
                            'text' => __('filament-mia::page-builder.shared.styles.text'),
                        ])
                        ->default('solid')
                        ->selectablePlaceholder(false)
                        ->native(false),
                ]),
            ]);
    }

    protected static function eyebrow(): TextInput
    {
        return TextInput::make('eyebrow')
            ->label(__('filament-mia::page-builder.shared.eyebrow'))
            ->helperText(__('filament-mia::page-builder.shared.eyebrow_help'))
            ->maxLength(60);
    }

    protected static function links(int $maxItems): Repeater
    {
        return Repeater::make('links')
            ->label(__('filament-mia::page-builder.shared.links'))
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->collapsible()
            ->collapsed()
            ->maxItems($maxItems)
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('label')
                        ->label(__('filament-mia::page-builder.shared.action_label'))
                        ->required()
                        ->maxLength(40),
                    TextInput::make('url')
                        ->label(__('filament-mia::page-builder.shared.action_url'))
                        ->required()
                        ->maxLength(300),
                ]),
            ]);
    }

    /**
     * An image field, on the public disk so the rendered page can address it
     * without a signed route.
     */
    protected static function image(string $name, string $label, int $maxSize = 2048): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('mia-page')
            ->visibility('public')
            ->maxSize($maxSize);
    }

    protected static function navigation(): Block
    {
        return Block::make('navigation')
            ->label(__('filament-mia::page-builder.blocks.navigation.label'))
            ->icon(Heroicon::OutlinedBars3)
            ->maxItems(1)
            ->schema([
                ...static::shared(offersSurface: false),
                Grid::make(2)->schema([
                    TextInput::make('brand')
                        ->label(__('filament-mia::page-builder.blocks.navigation.brand'))
                        ->helperText(__('filament-mia::page-builder.blocks.navigation.brand_help'))
                        ->maxLength(60),
                    static::image('logo', __('filament-mia::page-builder.blocks.navigation.logo'), 512)
                        ->helperText(__('filament-mia::page-builder.blocks.navigation.logo_help')),
                ]),
                Grid::make(2)->schema([
                    Toggle::make('sticky')
                        ->label(__('filament-mia::page-builder.blocks.navigation.sticky'))
                        ->default(true)
                        ->inline(false),
                    Toggle::make('scheme_toggle')
                        ->label(__('filament-mia::page-builder.blocks.navigation.scheme_toggle'))
                        ->helperText(__('filament-mia::page-builder.blocks.navigation.scheme_toggle_help'))
                        ->default(true)
                        ->inline(false),
                ]),
                static::links(maxItems: 5),
                static::actions(__('filament-mia::page-builder.blocks.navigation.actions'), maxItems: 2),
            ]);
    }

    protected static function hero(): Block
    {
        return Block::make('hero')
            ->label(__('filament-mia::page-builder.blocks.hero.label'))
            ->icon(Heroicon::OutlinedSparkles)
            ->maxItems(1)
            ->schema([
                ...static::shared(defaultSurface: 'warm'),
                static::eyebrow(),
                Textarea::make('heading')
                    ->label(__('filament-mia::page-builder.blocks.hero.heading'))
                    ->rows(2)
                    ->required()
                    ->maxLength(160),
                TextInput::make('heading_accent')
                    ->label(__('filament-mia::page-builder.blocks.hero.heading_accent'))
                    ->helperText(__('filament-mia::page-builder.blocks.hero.heading_accent_help'))
                    ->maxLength(80),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(3)
                    ->maxLength(400),
                static::actions(),
                static::image('image', __('filament-mia::page-builder.blocks.hero.image'))
                    ->imageEditor()
                    ->helperText(__('filament-mia::page-builder.blocks.hero.image_help')),
                TextInput::make('image_alt')
                    ->label(__('filament-mia::page-builder.blocks.hero.image_alt'))
                    ->helperText(__('filament-mia::page-builder.blocks.hero.image_alt_help'))
                    ->maxLength(160),
                Fieldset::make(__('filament-mia::page-builder.blocks.hero.card'))
                    ->schema([
                        Toggle::make('card_visible')
                            ->label(__('filament-mia::page-builder.blocks.hero.card_visible'))
                            ->default(true)
                            ->inline(false),
                        Grid::make(2)->schema([
                            TextInput::make('card_eyebrow')
                                ->label(__('filament-mia::page-builder.blocks.hero.card_eyebrow'))
                                ->maxLength(40),
                            TextInput::make('card_note')
                                ->label(__('filament-mia::page-builder.blocks.hero.card_note'))
                                ->maxLength(40),
                        ]),
                        TextInput::make('card_title')
                            ->label(__('filament-mia::page-builder.blocks.hero.card_title'))
                            ->maxLength(60),
                        TextInput::make('card_subtitle')
                            ->label(__('filament-mia::page-builder.blocks.hero.card_subtitle'))
                            ->maxLength(80),
                        Repeater::make('card_rows')
                            ->label(__('filament-mia::page-builder.blocks.hero.card_rows'))
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->collapsible()
                            ->collapsed()
                            ->maxItems(4)
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('label')
                                        ->label(__('filament-mia::page-builder.blocks.hero.card_row_label'))
                                        ->maxLength(32),
                                    TextInput::make('title')
                                        ->label(__('filament-mia::page-builder.blocks.hero.card_row_title'))
                                        ->maxLength(60),
                                    TextInput::make('note')
                                        ->label(__('filament-mia::page-builder.blocks.hero.card_row_note'))
                                        ->maxLength(60),
                                ]),
                            ]),
                    ]),
            ]);
    }

    protected static function features(): Block
    {
        return Block::make('features')
            ->label(__('filament-mia::page-builder.blocks.features.label'))
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                ...static::shared(),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                TextInput::make('heading_quiet')
                    ->label(__('filament-mia::page-builder.shared.heading_quiet'))
                    ->helperText(__('filament-mia::page-builder.shared.heading_quiet_help'))
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Select::make('columns')
                    ->label(__('filament-mia::page-builder.blocks.features.columns'))
                    ->options([
                        2 => __('filament-mia::page-builder.blocks.features.two'),
                        3 => __('filament-mia::page-builder.blocks.features.three'),
                    ])
                    ->default(3)
                    ->selectablePlaceholder(false)
                    ->native(false),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.features.items'))
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(3)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label(__('filament-mia::page-builder.shared.title'))
                                ->required()
                                ->maxLength(60),
                            Select::make('icon')
                                ->label(__('filament-mia::page-builder.shared.icon'))
                                ->options(static::iconOptions())
                                ->native(false)
                                ->searchable(),
                        ]),
                        Textarea::make('text')
                            ->label(__('filament-mia::page-builder.shared.description'))
                            ->rows(2)
                            ->maxLength(280),
                    ]),
            ]);
    }

    protected static function steps(): Block
    {
        return Block::make('steps')
            ->label(__('filament-mia::page-builder.blocks.steps.label'))
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                ...static::shared(defaultSurface: 'deep'),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.steps.items'))
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(3)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-mia::page-builder.blocks.steps.title'))
                            ->required()
                            ->maxLength(80),
                        Textarea::make('text')
                            ->label(__('filament-mia::page-builder.shared.description'))
                            ->rows(2)
                            ->maxLength(320),
                    ]),
            ]);
    }

    protected static function comparison(): Block
    {
        return Block::make('comparison')
            ->label(__('filament-mia::page-builder.blocks.comparison.label'))
            ->icon(Heroicon::OutlinedTableCells)
            ->schema([
                ...static::shared(),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                TextInput::make('heading_quiet')
                    ->label(__('filament-mia::page-builder.shared.heading_quiet'))
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Fieldset::make(__('filament-mia::page-builder.blocks.comparison.columns'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('primary_title')
                            ->label(__('filament-mia::page-builder.blocks.comparison.primary_title'))
                            ->maxLength(60),
                        TextInput::make('secondary_title')
                            ->label(__('filament-mia::page-builder.blocks.comparison.secondary_title'))
                            ->maxLength(60),
                        TextInput::make('primary_subtitle')
                            ->label(__('filament-mia::page-builder.shared.subtitle'))
                            ->maxLength(80),
                        TextInput::make('secondary_subtitle')
                            ->label(__('filament-mia::page-builder.shared.subtitle'))
                            ->maxLength(80),
                    ]),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.comparison.items'))
                    ->itemLabel(fn (array $state): ?string => $state['criterion'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(3)
                    ->schema([
                        TextInput::make('criterion')
                            ->label(__('filament-mia::page-builder.blocks.comparison.criterion'))
                            ->required()
                            ->maxLength(60),
                        Grid::make(2)->schema([
                            TextInput::make('primary')
                                ->label(__('filament-mia::page-builder.blocks.comparison.primary_value'))
                                ->maxLength(60),
                            TextInput::make('secondary')
                                ->label(__('filament-mia::page-builder.blocks.comparison.secondary_value'))
                                ->maxLength(60),
                        ]),
                    ]),
            ]);
    }

    protected static function metrics(): Block
    {
        return Block::make('metrics')
            ->label(__('filament-mia::page-builder.blocks.metrics.label'))
            ->icon(Heroicon::OutlinedChartBar)
            ->schema([
                ...static::shared(defaultSurface: 'warm'),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->maxLength(120),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.metrics.items'))
                    ->itemLabel(fn (array $state): ?string => $state['value'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(3)
                    ->maxItems(4)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('value')
                                ->label(__('filament-mia::page-builder.blocks.metrics.value'))
                                ->required()
                                ->maxLength(20),
                            TextInput::make('label')
                                ->label(__('filament-mia::page-builder.blocks.metrics.label_field'))
                                ->required()
                                ->maxLength(80),
                        ]),
                    ]),
            ]);
    }

    protected static function testimonials(): Block
    {
        return Block::make('testimonials')
            ->label(__('filament-mia::page-builder.blocks.testimonials.label'))
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->schema([
                ...static::shared(defaultSurface: 'raised'),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.testimonials.items'))
                    ->itemLabel(fn (array $state): ?string => $state['author'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(2)
                    ->schema([
                        Textarea::make('quote')
                            ->label(__('filament-mia::page-builder.blocks.testimonials.quote'))
                            ->rows(3)
                            ->required()
                            ->maxLength(400),
                        Grid::make(2)->schema([
                            TextInput::make('author')
                                ->label(__('filament-mia::page-builder.blocks.testimonials.author'))
                                ->required()
                                ->maxLength(60),
                            TextInput::make('role')
                                ->label(__('filament-mia::page-builder.blocks.testimonials.role'))
                                ->maxLength(80),
                        ]),
                        static::image('avatar', __('filament-mia::page-builder.blocks.testimonials.avatar'), 1024)
                            ->avatar(),
                    ]),
            ]);
    }

    protected static function pricing(): Block
    {
        return Block::make('pricing')
            ->label(__('filament-mia::page-builder.blocks.pricing.label'))
            ->icon(Heroicon::OutlinedBanknotes)
            ->schema([
                ...static::shared(),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.pricing.items'))
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->maxItems(4)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('filament-mia::page-builder.blocks.pricing.name'))
                                ->required()
                                ->maxLength(40),
                            Toggle::make('featured')
                                ->label(__('filament-mia::page-builder.blocks.pricing.featured'))
                                ->inline(false),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('price')
                                ->label(__('filament-mia::page-builder.blocks.pricing.price'))
                                ->helperText(__('filament-mia::page-builder.blocks.pricing.price_help'))
                                ->maxLength(24),
                            TextInput::make('period')
                                ->label(__('filament-mia::page-builder.blocks.pricing.period'))
                                ->maxLength(24),
                        ]),
                        Textarea::make('description')
                            ->label(__('filament-mia::page-builder.blocks.pricing.description'))
                            ->rows(2)
                            ->maxLength(200),
                        Textarea::make('features')
                            ->label(__('filament-mia::page-builder.blocks.pricing.features'))
                            ->helperText(__('filament-mia::page-builder.blocks.pricing.features_help'))
                            ->rows(5)
                            ->maxLength(800),
                        Grid::make(2)->schema([
                            TextInput::make('action_label')
                                ->label(__('filament-mia::page-builder.blocks.pricing.action_label'))
                                ->maxLength(40),
                            TextInput::make('action_url')
                                ->label(__('filament-mia::page-builder.blocks.pricing.action_url'))
                                ->maxLength(300),
                        ]),
                    ]),
                TextInput::make('note')
                    ->label(__('filament-mia::page-builder.blocks.pricing.note'))
                    ->maxLength(200),
            ]);
    }

    protected static function faq(): Block
    {
        return Block::make('faq')
            ->label(__('filament-mia::page-builder.blocks.faq.label'))
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                ...static::shared(defaultSurface: 'warm'),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                Repeater::make('items')
                    ->label(__('filament-mia::page-builder.blocks.faq.items'))
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                    ->collapsible()
                    ->collapsed()
                    ->minItems(1)
                    ->defaultItems(3)
                    ->schema([
                        TextInput::make('question')
                            ->label(__('filament-mia::page-builder.blocks.faq.question'))
                            ->required()
                            ->maxLength(160),
                        Textarea::make('answer')
                            ->label(__('filament-mia::page-builder.blocks.faq.answer'))
                            ->rows(3)
                            ->required()
                            ->maxLength(700),
                    ]),
            ]);
    }

    protected static function callToAction(): Block
    {
        return Block::make('call_to_action')
            ->label(__('filament-mia::page-builder.blocks.call_to_action.label'))
            ->icon(Heroicon::OutlinedMegaphone)
            ->schema([
                ...static::shared(defaultSurface: 'deep'),
                static::eyebrow(),
                TextInput::make('heading')
                    ->label(__('filament-mia::page-builder.shared.heading_text'))
                    ->required()
                    ->maxLength(120),
                Textarea::make('lead')
                    ->label(__('filament-mia::page-builder.shared.lead'))
                    ->rows(2)
                    ->maxLength(320),
                static::actions(),
            ]);
    }

    protected static function footer(): Block
    {
        return Block::make('footer')
            ->label(__('filament-mia::page-builder.blocks.footer.label'))
            ->icon(Heroicon::OutlinedRectangleGroup)
            ->maxItems(1)
            ->schema([
                ...static::shared(defaultSurface: 'deep'),
                TextInput::make('brand')
                    ->label(__('filament-mia::page-builder.blocks.navigation.brand'))
                    ->helperText(__('filament-mia::page-builder.blocks.navigation.brand_help'))
                    ->maxLength(60),
                Textarea::make('description')
                    ->label(__('filament-mia::page-builder.blocks.footer.description'))
                    ->rows(2)
                    ->maxLength(240),
                static::links(maxItems: 6),
                TextInput::make('legal')
                    ->label(__('filament-mia::page-builder.blocks.footer.legal'))
                    ->maxLength(200),
            ]);
    }
}
