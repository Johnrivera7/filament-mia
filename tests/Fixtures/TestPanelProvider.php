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
            // Named, so the assertions about the way back off an error page
            // are about the theme's wording rather than about Filament's
            // fallback to the application name.
            ->brandName('Studio')
            // A sign-in screen, so the 419 page has somewhere to send a
            // visitor whose session has expired.
            ->login()
            ->plugin(MiaTheme::make()->customizer());
    }
}
