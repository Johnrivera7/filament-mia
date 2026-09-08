<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

/**
 * Work closed per week.
 *
 * The series is a fixed list rather than a query. The preview panel is
 * photographed, and a chart drawn from randomised records would produce a
 * different picture on every build — which makes the frames unusable for
 * comparing one version of the theme with the next.
 */
class DeliveryChart extends ChartWidget
{
    protected ?string $heading = 'Delivery pace';

    protected ?string $description = 'Items closed per week.';

    protected static ?int $sort = 1;

    protected ?string $maxHeight = '14rem';

    protected int|string|array $columnSpan = 1;

    // Every colour comes from the panel's palette through Filament's colour
    // system, so the chart follows the theme and the appearance page without a
    // single colour value written here.
    protected string $color = 'primary';

    /** @var array<int, int> */
    protected const SERIES = [24, 31, 28, 35, 33, 41, 38, 46, 44, 52, 49, 57];

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Closed',
                    'data' => self::SERIES,
                    'fill' => 'start',
                ],
            ],
            'labels' => collect(range(count(self::SERIES) - 1, 0))
                ->map(fn (int $weeksAgo): string => Carbon::parse('2026-09-07')
                    ->subWeeks($weeksAgo)
                    ->translatedFormat('j M'))
                ->all(),
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
