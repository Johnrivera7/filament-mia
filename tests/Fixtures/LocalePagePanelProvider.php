<?php

namespace JohnRivera7\FilamentMia\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JohnRivera7\FilamentMia\MiaTheme;

/**
 * A panel with both the page builder and the language switcher on, which is
 * the only arrangement in which the public page has a language to offer.
 */
class LocalePagePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('bilingual')
            ->path('bilingual')
            ->login()
            ->plugin(
                MiaTheme::make()
                    ->pageBuilder()
                    ->localeSwitcher(['en', 'es']),
            );
    }
}
