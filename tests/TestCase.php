<?php

namespace JohnRivera7\FilamentMia\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use JohnRivera7\FilamentMia\FilamentMiaServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentMiaServiceProvider::class,
        ];
    }
}
