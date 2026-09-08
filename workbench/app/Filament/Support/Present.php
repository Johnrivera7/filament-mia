<?php

namespace Workbench\App\Filament\Support;

/**
 * Labels, colours and icons for the states the preview records carry.
 *
 * Kept in one place so the table, the form and the widgets agree, and so the
 * badge colours exercise every palette the theme defines rather than only the
 * accent.
 */
class Present
{
    /** @var array<string, array<string, array{label: string, color: string, icon?: string}>> */
    protected const MAPS = [
        'status' => [
            'discovery' => ['label' => 'Discovery', 'color' => 'gray', 'icon' => 'heroicon-m-magnifying-glass'],
            'design' => ['label' => 'Design', 'color' => 'secondary', 'icon' => 'heroicon-m-pencil'],
            'build' => ['label' => 'Build', 'color' => 'primary', 'icon' => 'heroicon-m-wrench-screwdriver'],
            'review' => ['label' => 'In review', 'color' => 'info', 'icon' => 'heroicon-m-eye'],
            'delivered' => ['label' => 'Delivered', 'color' => 'success', 'icon' => 'heroicon-m-check-badge'],
            'on_hold' => ['label' => 'On hold', 'color' => 'warning', 'icon' => 'heroicon-m-pause'],
        ],
        'health' => [
            'on_track' => ['label' => 'On track', 'color' => 'success'],
            'at_risk' => ['label' => 'At risk', 'color' => 'warning'],
            'blocked' => ['label' => 'Blocked', 'color' => 'danger'],
        ],
        'priority' => [
            'low' => ['label' => 'Low', 'color' => 'gray'],
            'normal' => ['label' => 'Normal', 'color' => 'info'],
            'high' => ['label' => 'High', 'color' => 'danger'],
        ],
        'tier' => [
            'retainer' => ['label' => 'Retainer', 'color' => 'primary'],
            'project' => ['label' => 'Project work', 'color' => 'gray'],
            'prospect' => ['label' => 'Prospect', 'color' => 'secondary'],
        ],
    ];

    public static function label(string $map, ?string $value): string
    {
        return self::MAPS[$map][$value]['label'] ?? '—';
    }

    public static function color(string $map, ?string $value): string
    {
        return self::MAPS[$map][$value]['color'] ?? 'gray';
    }

    public static function icon(string $map, ?string $value): ?string
    {
        return self::MAPS[$map][$value]['icon'] ?? null;
    }

    /** @return array<string, string> */
    public static function options(string $map): array
    {
        return array_map(fn (array $entry): string => $entry['label'], self::MAPS[$map]);
    }
}
