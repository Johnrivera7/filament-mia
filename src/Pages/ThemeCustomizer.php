<?php

namespace JohnRivera7\FilamentMia\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JohnRivera7\FilamentMia\Settings\Presets;
use JohnRivera7\FilamentMia\Settings\ThemeSettings;
use JohnRivera7\FilamentMia\Support\FontLibrary;
use JohnRivera7\FilamentMia\Support\PreviewSheet;
use UnitEnum;

/**
 * Edit the theme from inside the panel.
 *
 * The preview is not an approximation. Every control writes a custom property
 * that the compiled stylesheet already reads, and the same `PreviewSheet` that
 * paints the preview is what the theme emits once the settings are saved, so
 * what is on screen before saving is what the panel becomes after.
 *
 * Nothing here can generate a Tailwind class. That is the constraint that
 * makes a pre-compiled theme configurable at all: the stylesheet is fixed at
 * build time and only the values it reads can change.
 *
 * @property-read Schema $form
 */
class ThemeCustomizer extends Page
{
    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static bool $isDiscovered = false;

    protected string $view = 'filament-mia::pages.theme-customizer';

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return MiaTheme::get()->getCustomizerNavigationIcon() ?? Heroicon::OutlinedSwatch;
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return MiaTheme::get()->getCustomizerNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return MiaTheme::get()->getCustomizerNavigationSort();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-mia::customizer.title');
    }

    public function getTitle(): string
    {
        return __('filament-mia::customizer.title');
    }

    public function getSubheading(): ?string
    {
        return __('filament-mia::customizer.subheading');
    }

    public static function canAccess(): bool
    {
        return MiaTheme::get()->isCustomizerAuthorized();
    }

    public function mount(): void
    {
        $this->form->fill($this->currentSettings()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                $this->presetSection(),
                $this->colourSection(),
                $this->typographySection(),
                $this->shapeSection(),
                $this->depthSection(),
            ]);
    }

    protected function presetSection(): Section
    {
        return Section::make(__('filament-mia::customizer.presets.heading'))
            ->description(__('filament-mia::customizer.presets.description'))
            ->schema([
                Radio::make('preset')
                    ->hiddenLabel()
                    ->options(Presets::options())
                    ->descriptions(Presets::descriptions())
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if ($state === null || ($settings = Presets::settings($state)) === null) {
                            return;
                        }

                        // Set each key rather than replacing the whole record:
                        // a preset carries no status colours, which are a
                        // separate decision and should survive applying one.
                        foreach ($settings as $key => $value) {
                            $set($key, $value);
                        }

                        $set('custom_neutral', filled($settings['neutral_color'] ?? null));

                        $this->refreshPreview();
                    }),
            ]);
    }

    protected function colourSection(): Section
    {
        return Section::make(__('filament-mia::customizer.colour.heading'))
            ->description(__('filament-mia::customizer.colour.description'))
            ->columns(2)
            ->schema([
                $this->preview(ColorPicker::make('accent_color'))
                    ->label(__('filament-mia::customizer.colour.accent'))
                    ->helperText(__('filament-mia::customizer.colour.accent_help'))
                    ->required(),

                $this->preview(ColorPicker::make('secondary_color'))
                    ->label(__('filament-mia::customizer.colour.secondary'))
                    ->helperText(__('filament-mia::customizer.colour.secondary_help'))
                    ->required(),

                Toggle::make('custom_neutral')
                    ->label(__('filament-mia::customizer.colour.custom_neutral'))
                    ->helperText(__('filament-mia::customizer.colour.custom_neutral_help'))
                    ->live()
                    ->dehydrated(false)
                    ->default(fn (): bool => filled($this->data['neutral_color'] ?? null))
                    ->afterStateUpdated(function (bool $state, Set $set): void {
                        $set('neutral_color', $state ? '#8A7F6E' : null);

                        $this->refreshPreview();
                    })
                    ->columnSpanFull(),

                $this->preview(ColorPicker::make('neutral_color'))
                    ->label(__('filament-mia::customizer.colour.neutral'))
                    ->helperText(__('filament-mia::customizer.colour.neutral_help'))
                    ->visible(fn (Get $get): bool => (bool) $get('custom_neutral'))
                    ->columnSpanFull(),

                $this->preview(ColorPicker::make('danger_color'))
                    ->label(__('filament-mia::customizer.colour.danger'))
                    ->required(),

                $this->preview(ColorPicker::make('warning_color'))
                    ->label(__('filament-mia::customizer.colour.warning'))
                    ->required(),

                $this->preview(ColorPicker::make('success_color'))
                    ->label(__('filament-mia::customizer.colour.success'))
                    ->required(),

                $this->preview(ColorPicker::make('info_color'))
                    ->label(__('filament-mia::customizer.colour.info'))
                    ->required(),
            ]);
    }

    protected function typographySection(): Section
    {
        return Section::make(__('filament-mia::customizer.type.heading'))
            ->description(__('filament-mia::customizer.type.description'))
            ->columns(2)
            ->schema([
                $this->preview(Select::make('sans_font'))
                    ->label(__('filament-mia::customizer.type.sans'))
                    ->options(FontLibrary::sans())
                    ->searchable()
                    ->required(),

                $this->preview(Select::make('serif_font'))
                    ->label(__('filament-mia::customizer.type.serif'))
                    ->options(FontLibrary::serif())
                    ->searchable()
                    ->required(),

                $this->preview(Toggle::make('serif_headings'))
                    ->label(__('filament-mia::customizer.type.serif_headings'))
                    ->helperText(__('filament-mia::customizer.type.serif_headings_help'))
                    ->columnSpanFull(),
            ]);
    }

    protected function shapeSection(): Section
    {
        return Section::make(__('filament-mia::customizer.shape.heading'))
            ->description(__('filament-mia::customizer.shape.description'))
            ->columns(2)
            ->schema([
                $this->preview(ToggleButtons::make('roundness'))
                    ->label(__('filament-mia::customizer.shape.roundness'))
                    ->options(array_combine(
                        Roundness::values(),
                        array_map(
                            fn (string $value): string => __("filament-mia::customizer.shape.roundness_options.{$value}"),
                            Roundness::values(),
                        ),
                    ))
                    ->inline()
                    ->required(),

                $this->preview(ToggleButtons::make('density'))
                    ->label(__('filament-mia::customizer.shape.density'))
                    ->options(array_combine(
                        Density::values(),
                        array_map(
                            fn (string $value): string => __("filament-mia::customizer.shape.density_options.{$value}"),
                            Density::values(),
                        ),
                    ))
                    ->inline()
                    ->required(),
            ]);
    }

    protected function depthSection(): Section
    {
        return Section::make(__('filament-mia::customizer.depth.heading'))
            ->description(__('filament-mia::customizer.depth.description'))
            ->columns(2)
            ->schema([
                $this->preview(Slider::make('elevation'))
                    ->label(__('filament-mia::customizer.depth.elevation'))
                    ->helperText(__('filament-mia::customizer.depth.elevation_help'))
                    ->range(0, 2)
                    ->step(0.1)
                    ->tooltips(),

                $this->preview(Toggle::make('motion'))
                    ->label(__('filament-mia::customizer.depth.motion'))
                    ->helperText(__('filament-mia::customizer.depth.motion_help')),
            ]);
    }

    /**
     * Make a field drive the preview.
     *
     * Debounced rather than immediate: a colour picker emits a value on every
     * pointer move, and each one costs a round trip to rebuild the ramps.
     *
     * @template TField of Field
     *
     * @param  TField  $field
     * @return TField
     */
    protected function preview(Field $field): Field
    {
        return $field
            ->live(debounce: 300)
            ->afterStateUpdated(fn () => $this->refreshPreview());
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('reset')
                ->label(__('filament-mia::customizer.actions.reset'))
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->color('gray')
                ->link()
                ->requiresConfirmation()
                ->modalHeading(__('filament-mia::customizer.actions.reset_heading'))
                ->modalDescription(__('filament-mia::customizer.actions.reset_description'))
                ->action(fn () => $this->reset_()),

            Action::make('save')
                ->label(__('filament-mia::customizer.actions.save'))
                ->icon(Heroicon::OutlinedCheck)
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        try {
            /** @var array<string, mixed> $state */
            $state = $this->form->getState();

            $settings = ThemeSettings::fromArray($state);
        } catch (InvalidThemeOption $exception) {
            Notification::make()
                ->title(__('filament-mia::customizer.notifications.invalid'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->repository()->put($this->panelId(), $settings->toArray());

        Notification::make()
            ->title(__('filament-mia::customizer.notifications.saved'))
            ->success()
            ->send();
    }

    /**
     * Discard the saved record, returning the panel to the values in
     * `config/filament-mia.php` and the fluent API.
     *
     * Named with a trailing underscore because Livewire's `Component::reset()`
     * is a different thing entirely and overriding it would break the form.
     */
    public function reset_(): void
    {
        $this->repository()->forget($this->panelId());

        $this->form->fill(ThemeSettings::defaults()->toArray());

        $this->refreshPreview();

        Notification::make()
            ->title(__('filament-mia::customizer.notifications.reset'))
            ->success()
            ->send();
    }

    /**
     * Rebuild the preview stylesheet and hand it to the browser.
     *
     * Invalid input is expected while typing a colour, so a failure here only
     * leaves the previous preview in place rather than raising anything.
     */
    public function refreshPreview(): void
    {
        try {
            /** @var array<string, mixed> $state */
            $state = $this->form->getRawState();

            $settings = ThemeSettings::fromArray($state);
        } catch (InvalidThemeOption) {
            return;
        }

        $sheet = new PreviewSheet($this->panelId(), $settings);

        $this->dispatch(
            'mia-preview',
            css: $sheet->css(),
            fonts: $sheet->fontUrls(),
        );
    }

    protected function currentSettings(): ThemeSettings
    {
        return ThemeSettings::fromArray(
            $this->repository()->get($this->panelId()),
            MiaTheme::get()->toSettings(),
        );
    }

    protected function repository(): SettingsRepository
    {
        return app(SettingsRepository::class);
    }

    protected function panelId(): string
    {
        return filament()->getCurrentOrDefaultPanel()?->getId() ?? 'default';
    }
}
