<?php

namespace JohnRivera7\FilamentMia\Settings\Contracts;

/**
 * Where the customiser's saved settings live.
 *
 * Records are keyed by panel id, which is the scope the theme is designed
 * around: a panel's appearance is a property of the panel, not of whoever is
 * looking at it. See the README for the reasoning and for how to store the
 * settings per user instead by binding your own implementation.
 *
 * Implementations must not throw when a panel has never been customised;
 * return an empty array instead.
 */
interface SettingsRepository
{
    /**
     * @return array<string, mixed>
     */
    public function get(string $panelId): array;

    /**
     * @param  array<string, mixed>  $settings
     */
    public function put(string $panelId, array $settings): void;

    /**
     * Discard a panel's saved settings, returning it to the values in
     * `config/filament-mia.php` and the fluent API.
     */
    public function forget(string $panelId): void;
}
