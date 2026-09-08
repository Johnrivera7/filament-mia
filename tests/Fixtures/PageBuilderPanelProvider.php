<?php

namespace JohnRivera7\FilamentMia\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JohnRivera7\FilamentMia\MiaTheme;

/**
 * A panel with the page builder switched on, so the feature can be exercised
 * the way an application would reach it rather than by calling its methods in
 * isolation.
 */
class PageBuilderPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('builder')
            ->path('builder')
            ->brandName('Studio')
            ->login()
            ->plugin(MiaTheme::make()->pageBuilder());
    }
}
