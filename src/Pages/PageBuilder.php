<?php

namespace JohnRivera7\FilamentMia\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\PageBuilder\BlockCatalog;
use JohnRivera7\FilamentMia\PageBuilder\DefaultContent;
use JohnRivera7\FilamentMia\PageBuilder\Models\MiaPage;
use UnitEnum;

/**
 * Compose the public page from a catalogue of sections.
 *
 * Saving and publishing are separate, and the split is the whole design.
 * A save only writes `draft`, and does so without validating, because a
 * half-finished section is a normal state to leave the builder in — someone
 * adds a block and goes to lunch. Publishing validates and copies the draft
 * over the payload the public page reads, which also drops its cache.
 *
 * The preview is an iframe of the draft route rather than an in-page render:
 * it is the real page, in a real viewport, with the real stylesheet — so what
 * it shows is what will be published, including the responsive behaviour that
 * a scaled-down component preview would quietly get wrong.
 *
 * @property-read Schema $form
 */
class PageBuilder extends Page
{
    /**
     * The viewports the preview can be framed at, in CSS pixels.
     *
     * Real widths rather than a fraction of the pane, which is the whole
     * point. A preview that is only as wide as the column it sits in renders
     * the page's narrow layout and calls it desktop; what the pane cannot fit
     * is scaled down instead, so the layout on screen is the layout a visitor
     * at that width would get.
     */
    public const WIDTHS = [
        'mobile' => 390,
        'tablet' => 834,
        'desktop' => 1280,
    ];

    protected static bool $isDiscovered = false;

    protected string $view = 'filament-mia::pages.page-builder';

    /** The preview pane needs the full width of the window to be worth having. */
    protected Width|string|null $maxContentWidth = Width::Full;

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    /**
     * When on, leaving a field saves the draft and refreshes the preview,
     * which is what makes the pane feel live. Off by default: it costs a round
     * trip per field, and on a slow connection that is worse than a button.
     */
    public bool $livePreview = false;

    public string $previewWidth = 'desktop';

    public bool $hasUnpublishedChanges = false;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return MiaTheme::get()->getPageBuilderNavigationIcon() ?? Heroicon::OutlinedNewspaper;
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return MiaTheme::get()->getPageBuilderNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return MiaTheme::get()->getPageBuilderNavigationSort();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-mia::page-builder.navigation');
    }

    public static function canAccess(): bool
    {
        return MiaTheme::get()->isPageBuilderAuthorized();
    }

    public function getTitle(): string
    {
        return __('filament-mia::page-builder.title');
    }

    public function getSubheading(): ?string
    {
        return __('filament-mia::page-builder.subheading');
    }

    public function mount(): void
    {
        $page = MiaPage::forKey();

        $this->form->fill([
            'blocks' => $page->draft['blocks'] ?? DefaultContent::blocks(),
        ]);

        $this->hasUnpublishedChanges = $page->hasUnpublishedChanges();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->live(onBlur: true)
            ->components([
                Builder::make('blocks')
                    ->label(__('filament-mia::page-builder.sections'))
                    ->blocks(BlockCatalog::blocks())
                    ->addActionLabel(__('filament-mia::page-builder.add_section'))
                    ->blockIcons()
                    ->blockNumbers(false)
                    ->collapsible()
                    ->collapsed()
                    ->cloneable()
                    ->blockPickerColumns(2)
                    ->blockPickerWidth(Width::ExtraLarge)
                    ->reorderableWithDragAndDrop()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label(__('filament-mia::page-builder.actions.publish'))
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->badge(fn (): ?string => $this->hasUnpublishedChanges
                    ? __('filament-mia::page-builder.actions.unpublished')
                    : null)
                ->action('publish'),

            Action::make('open_draft')
                ->label(__('filament-mia::page-builder.actions.open_draft'))
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(fn (): string => $this->previewUrl(), shouldOpenInNewTab: true),

            Action::make('reset')
                ->label(__('filament-mia::page-builder.actions.reset'))
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading(__('filament-mia::page-builder.actions.reset'))
                ->modalDescription(__('filament-mia::page-builder.actions.reset_description'))
                ->modalSubmitActionLabel(__('filament-mia::page-builder.actions.reset_confirm'))
                ->action('restoreStarter'),
        ];
    }

    /**
     * Livewire fires this for any change under `data`. Guarded by the toggle
     * so the round trip is opt-in.
     */
    public function updatedData(): void
    {
        if ($this->livePreview) {
            $this->saveDraft(notify: false);
        }
    }

    public function save(): void
    {
        $this->saveDraft();
    }

    public function publish(): void
    {
        // Publishing is the one moment validation is worth interrupting for:
        // it is the step that puts the page in front of a visitor.
        $state = $this->form->getState();

        $page = MiaPage::forKey();

        $page->forceFill([
            'draft' => $this->withBlocks($page->draft, $state['blocks'] ?? []),
        ])->save();

        $page->publish();

        $this->hasUnpublishedChanges = false;
        $this->refreshPreview();

        Notification::make()
            ->title(__('filament-mia::page-builder.notifications.published'))
            ->body(__('filament-mia::page-builder.notifications.published_body'))
            ->success()
            ->send();
    }

    /**
     * Put the starter page back into the draft.
     *
     * Named around what it does rather than called `reset()`, which is a
     * Livewire method for clearing component properties and must not be
     * shadowed.
     */
    public function restoreStarter(): void
    {
        $this->form->fill(['blocks' => DefaultContent::blocks()]);

        $this->saveDraft();
    }

    public function toggleLivePreview(): void
    {
        $this->livePreview = ! $this->livePreview;

        if ($this->livePreview) {
            $this->saveDraft(notify: false);
        }
    }

    public function setPreviewWidth(string $width): void
    {
        $this->previewWidth = array_key_exists($width, self::WIDTHS) ? $width : 'desktop';

        // The pane refits itself rather than being re-keyed, so changing the
        // width does not reload the iframe and lose its scroll position.
        $this->dispatch('mia-refit-page-preview');
    }

    /** The viewport width the preview renders at, in CSS pixels. */
    public function previewViewportWidth(): int
    {
        return self::WIDTHS[$this->previewWidth] ?? self::WIDTHS['desktop'];
    }

    public function previewUrl(): string
    {
        return route('filament-mia.page.preview');
    }

    /**
     * Writes the draft from raw state, without validation.
     *
     * A draft is allowed to be incomplete — that is the difference between it
     * and the published page — so running the form's rules here would block
     * the save the moment someone added a section and left it empty.
     */
    protected function saveDraft(bool $notify = true): void
    {
        $page = MiaPage::forKey();

        $page->forceFill([
            'draft' => $this->withBlocks($page->draft, $this->data['blocks'] ?? []),
        ])->save();

        $this->hasUnpublishedChanges = $page->hasUnpublishedChanges();
        $this->refreshPreview();

        if ($notify) {
            Notification::make()
                ->title(__('filament-mia::page-builder.notifications.saved'))
                ->body(__('filament-mia::page-builder.notifications.saved_body'))
                ->success()
                ->send();
        }
    }

    /**
     * Replace only the blocks, leaving anything else in the payload alone.
     *
     * The builder keys its state by UUID; `array_values` drops the keys while
     * keeping the order, so what is stored is a plain ordered list.
     *
     * @param  array<string, mixed>|null  $current
     * @param  array<mixed>  $blocks
     * @return array<string, mixed>
     */
    protected function withBlocks(?array $current, array $blocks): array
    {
        return [
            ...($current ?? []),
            'blocks' => array_values($blocks),
        ];
    }

    protected function refreshPreview(): void
    {
        $this->dispatch('mia-refresh-page-preview');
    }
}
