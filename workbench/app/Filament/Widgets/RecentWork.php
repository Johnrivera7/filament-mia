<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Workbench\App\Filament\Support\Present;
use Workbench\App\Models\Project;

/**
 * A table on the dashboard, which is where most panels put one.
 *
 * Worth having in the preview panel because a table inside a widget is styled
 * differently from a resource list — no card of its own, a heading above it,
 * and rows that have to sit tighter.
 */
class RecentWork extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->inFlight()->with('client')->latest('starts_at')->limit(5))
            ->heading('In flight this week')
            ->description('Open work, most recently started first.')
            ->columns([
                TextColumn::make('name')
                    ->label('Project')
                    ->weight(FontWeight::Medium)
                    ->description(fn (Project $record): string => $record->client->name),

                TextColumn::make('status')
                    ->label('Stage')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Present::label('status', $state))
                    ->color(fn (?string $state): string => Present::color('status', $state)),

                TextColumn::make('health')
                    ->label('Health')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Present::label('health', $state))
                    ->color(fn (?string $state): string => Present::color('health', $state)),

                TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(fn (int $state): string => "{$state}%"),

                TextColumn::make('owner.name')
                    ->label('Lead')
                    ->placeholder('Unassigned'),

                TextColumn::make('ends_at')
                    ->label('Due')
                    ->date('j M Y')
                    ->placeholder('No date'),
            ])
            ->paginated(false)
            ->recordUrl(fn (Project $record): string => "/admin/projects/{$record->getKey()}/edit");
    }

    protected function getTableQueryStringIdentifier(): ?string
    {
        return 'recent';
    }
}
