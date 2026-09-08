<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Workbench\App\Filament\Support\Present;
use Workbench\App\Models\Project;

/**
 * Open work by stage.
 *
 * A bar chart alongside the line one, because the theme derives the bar's
 * corner radius from its own roundness token and the two shapes are how you
 * see that happening.
 */
class WorkMixChart extends ChartWidget
{
    protected ?string $heading = 'Work by stage';

    protected ?string $description = 'Open projects, from first contact to sign-off.';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '14rem';

    protected int|string|array $columnSpan = 1;

    protected string $color = 'primary';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $counts = Project::query()
            ->inFlight()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stages = ['discovery', 'design', 'build', 'review'];

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => array_map(fn (string $stage): int => (int) ($counts[$stage] ?? 0), $stages),
                ],
            ],
            'labels' => array_map(fn (string $stage): string => Present::label('status', $stage), $stages),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
