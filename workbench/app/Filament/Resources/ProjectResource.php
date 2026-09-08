<?php

namespace Workbench\App\Filament\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Workbench\App\Filament\Resources\ProjectResource\Pages\CreateProject;
use Workbench\App\Filament\Resources\ProjectResource\Pages\EditProject;
use Workbench\App\Filament\Resources\ProjectResource\Pages\ListProjects;
use Workbench\App\Filament\Support\Present;
use Workbench\App\Models\Project;

/**
 * A populated resource, so the table and the form can be photographed.
 *
 * Everything here is chosen to put a component the theme restyles on screen:
 * badges in four palettes, a progress meter, a summary row, grouped filters, a
 * record action menu, bulk actions and an illustrated empty state.
 */
class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Delivery';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->inFlight()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['client', 'owner']);
    }

    /** Whether anything exists at all, ignoring the table's own filters. */
    protected static function hasRecords(): bool
    {
        return static::getModel()::query()->exists();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('The work')
                    ->description('What was agreed, and who is carrying it.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Project')
                            ->required()
                            ->maxLength(120)
                            ->columnSpanFull(),

                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('owner_id')
                            ->label('Lead')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Unassigned'),

                        Textarea::make('brief')
                            ->label('Brief')
                            ->rows(3)
                            ->helperText('One or two lines. Anything longer belongs in the proposal.')
                            ->columnSpanFull(),
                    ]),

                Section::make('State')
                    ->description('Where it stands this week.')
                    ->columns(3)
                    ->schema([
                        Select::make('status')
                            ->label('Stage')
                            ->options(Present::options('status'))
                            ->required(),

                        Select::make('health')
                            ->label('Health')
                            ->options(Present::options('health'))
                            ->required(),

                        Select::make('priority')
                            ->label('Priority')
                            ->options(Present::options('priority'))
                            ->required(),

                        TextInput::make('progress')
                            ->label('Progress')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),

                        // Filament's own picker rather than the browser's: the
                        // native control is drawn by the operating system and
                        // no stylesheet reaches inside it.
                        DatePicker::make('starts_at')
                            ->label('Started')
                            ->native(false)
                            ->displayFormat('j M Y'),

                        DatePicker::make('ends_at')
                            ->label('Due')
                            ->native(false)
                            ->displayFormat('j M Y'),

                        Toggle::make('is_starred')
                            ->label('Pin to the top of the list')
                            ->columnSpanFull(),
                    ]),

                Section::make('Money')
                    ->description('Budget agreed, and what has been spent against it.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('budget')
                            ->label('Budget')
                            ->numeric()
                            ->prefix('€'),

                        TextInput::make('spent')
                            ->label('Spent')
                            ->numeric()
                            ->prefix('€'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn (Project $record): string => $record->code)
                    ->icon(fn (Project $record): ?string => $record->is_starred ? 'heroicon-m-star' : null)
                    ->iconColor('warning')
                    ->iconPosition('after'),

                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): ?string => $record->client?->city),

                TextColumn::make('status')
                    ->label('Stage')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state): string => Present::label('status', $state))
                    ->color(fn (?string $state): string => Present::color('status', $state))
                    ->icon(fn (?string $state): ?string => Present::icon('status', $state)),

                TextColumn::make('health')
                    ->label('Health')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state): string => Present::label('health', $state))
                    ->color(fn (?string $state): string => Present::color('health', $state)),

                TextColumn::make('progress')
                    ->label('Progress')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => "{$state}%"),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->money('EUR')
                    ->sortable()
                    ->alignEnd()
                    ->summarize(Sum::make()->label('Total')->money('EUR')),

                TextColumn::make('owner.name')
                    ->label('Lead')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Unassigned'),

                TextColumn::make('ends_at')
                    ->label('Due')
                    ->date('j M Y')
                    ->sortable()
                    ->placeholder('No date')
                    ->color(fn (Project $record): ?string => $record->isOverdue() ? 'danger' : null)
                    ->description(fn (Project $record): ?string => $record->isOverdue() ? 'Overdue' : null),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Stage')
                    ->options(Present::options('status'))
                    ->multiple(),

                SelectFilter::make('health')
                    ->label('Health')
                    ->options(Present::options('health'))
                    ->multiple(),

                SelectFilter::make('client')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_starred')
                    ->label('Pinned')
                    ->placeholder('All')
                    ->trueLabel('Pinned only')
                    ->falseLabel('Unpinned'),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    Action::make('deliver')
                        ->label('Mark as delivered')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (Project $record): bool => $record->delivered_at === null)
                        ->requiresConfirmation()
                        ->modalHeading('Close the project')
                        ->modalDescription('It will be marked delivered, at 100%, dated today.')
                        ->modalSubmitActionLabel('Close it')
                        ->action(function (Project $record): void {
                            $record->update([
                                'status' => 'delivered',
                                'health' => 'on_track',
                                'progress' => 100,
                                'delivered_at' => now(),
                            ]);

                            Notification::make()
                                ->title("{$record->name} delivered")
                                ->icon('heroicon-o-check-badge')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_starred', 'desc')
            ->striped()
            /*
             * Two empty states, not one.
             *
             * A list that is empty because nothing has been created yet and a
             * list that is empty because a filter excluded everything are
             * different problems, and offering "add the first project" to
             * someone who has eighteen of them behind a search box is the
             * wrong answer. `exists()` tells the two apart: the empty state
             * only renders once the filtered query came back with nothing.
             */
            ->emptyStateIcon(fn (): string => static::hasRecords()
                ? 'heroicon-o-funnel'
                : 'heroicon-o-rectangle-stack')
            ->emptyStateHeading(fn (): string => static::hasRecords()
                ? 'Nothing matches'
                : 'No projects yet')
            ->emptyStateDescription(fn (): string => static::hasRecords()
                ? 'There is work in here, but none of it fits the search and filters you have on.'
                : 'A project holds the tasks, the hours and the invoicing for one piece of work.')
            ->emptyStateActions([
                Action::make('clear')
                    ->label('Show everything again')
                    ->icon('heroicon-m-arrow-path')
                    ->color('gray')
                    ->visible(fn (): bool => static::hasRecords())
                    ->action(function (ListProjects $livewire): void {
                        $livewire->resetTableSearch();
                        $livewire->resetTableFiltersForm();
                    }),

                CreateAction::make()
                    ->label('Add the first project')
                    ->hidden(fn (): bool => static::hasRecords()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
