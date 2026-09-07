<?php

namespace Workbench\App\Providers;

use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;
use Filament\PanelProvider;
use JohnRivera7\FilamentMia\MiaTheme;

/**
 * A panel for looking at the theme without an application around it.
 *
 * `php vendor/bin/testbench serve` boots this, which is enough to open every
 * screen the theme styles before sign-in — the form, its validation errors,
 * registration, password recovery and the multi-factor challenge — against
 * invented data and nothing else.
 *
 * It exists so that the screenshots in the README come from the package
 * itself rather than from whatever application happened to be at hand, and so
 * that a change to the sign-in compositions can be checked in one command.
 */
class PreviewPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('preview')
            ->path('admin')
            ->brandName('Mía')
            ->login()
            ->registration()
            ->passwordReset()
            ->profile()
            ->multiFactorAuthentication([
                AppAuthentication::make(),
            ])
            ->authGuard('web')
            ->plugin(
                MiaTheme::make()
                    ->customizer()
                    ->loginTagline('Client work, kept in one place.'),
            );
    }
}
