<?php

namespace JohnRivera7\FilamentMia;

use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMiaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-mia';

    public static string $viewNamespace = 'filament-mia';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        /*
         * Registered here rather than inside the plugin's `register()` because
         * `php artisan filament:assets` — the command that copies the
         * stylesheet into `public/` — runs in the console, where no panel has
         * been built yet. An asset registered only from a plugin is invisible
         * to it, and the theme would never be published.
         *
         * A `Theme` asset, not a `Css` asset: Filament emits plain stylesheets
         * *before* the active theme's own `<link>`, so a `Css` asset would lose
         * the cascade to the very stylesheet it is meant to replace.
         */
        FilamentAsset::register([
            Theme::make('mia', __DIR__ . '/../resources/dist/mia.css'),
        ], 'johnrivera7/filament-mia');
    }
}
