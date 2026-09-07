<?php

namespace JohnRivera7\FilamentMia\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JohnRivera7\FilamentMia\MiaTheme;

/**
 * A minimal panel, so the customiser can be exercised the way an application
 * would reach it rather than by calling its methods in isolation.
 */
class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('testing')
            ->path('testing')
            ->plugin(MiaTheme::make()->customizer());
    }
}
