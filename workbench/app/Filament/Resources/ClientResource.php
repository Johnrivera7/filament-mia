<?php

namespace Workbench\App\Filament\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;
use Workbench\App\Filament\Resources\ClientResource\Pages\ListClients;
use Workbench\App\Filament\Support\Present;
use Workbench\App\Models\Client;

/**
 * A second resource, so the navigation has more than one entry and the theme
 * can be seen with a group that holds several items.
 */
class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('The client')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('industry')
                            ->label('Industry')
                            ->required(),

                        TextInput::make('city')
                            ->label('City')
                            ->required(),

                        Select::make('tier')
                            ->label('Engagement')
                            ->options(Present::options('tier'))
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn (Client $record): string => $record->industry),

                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tier')
                    ->label('Engagement')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state): string => Present::label('tier', $state))
                    ->color(fn (?string $state): string => Present::color('tier', $state)),

                TextColumn::make('projects_count')
                    ->label('Projects')
                    ->counts('projects')
                    ->badge()
                    ->color('gray')
                    ->alignEnd(),
            ])
            ->filters([
                SelectFilter::make('tier')
                    ->label('Engagement')
                    ->options(Present::options('tier')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateHeading('No clients yet')
            ->emptyStateDescription('Clients hold the work, the invoicing and the history of a relationship.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
        ];
    }
}
