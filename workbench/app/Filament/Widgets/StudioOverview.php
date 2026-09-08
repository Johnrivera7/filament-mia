<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Workbench\App\Models\Client;
use Workbench\App\Models\Project;

/**
 * Four numbers across the top of the dashboard.
 *
 * The sparklines are here on purpose: they are drawn by Chart.js through the
 * same custom properties the theme sets for full chart widgets, so a change to
 * roundness or palette shows up in them too.
 */
class StudioOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $inFlight = Project::query()->inFlight()->count();
        $delivered = Project::query()->whereNotNull('delivered_at')->count();
        $atRisk = Project::query()->whereIn('health', ['at_risk', 'blocked'])->count();
        $budget = (int) Project::query()->inFlight()->sum('budget');

        return [
            Stat::make('In flight', (string) $inFlight)
                ->description('Across ' . Client::query()->where('tier', '!=', 'prospect')->count() . ' clients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([4, 6, 5, 8, 7, 9, 11, 10])
                ->color('primary'),

            Stat::make('Delivered', (string) $delivered)
                ->description('Signed off this half')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([1, 2, 2, 3, 3, 4, 4, 5])
                ->color('success'),

            Stat::make('Needs attention', (string) $atRisk)
                ->description('At risk or blocked')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([3, 2, 4, 3, 5, 4, 6, 5])
                ->color('warning'),

            Stat::make('Committed', '€' . number_format($budget / 1000, 0) . 'k')
                ->description('Budget on open work')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([28, 34, 31, 40, 44, 42, 51, 58])
                ->color('secondary'),
        ];
    }
}
