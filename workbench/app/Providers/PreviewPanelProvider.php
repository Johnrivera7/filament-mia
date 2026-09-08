<?php

namespace Workbench\App\Providers;

use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use JohnRivera7\FilamentMia\MiaTheme;
use Workbench\App\Filament\Resources\ClientResource;
use Workbench\App\Filament\Resources\ProjectResource;
use Workbench\App\Filament\Widgets\DeliveryChart;
use Workbench\App\Filament\Widgets\RecentWork;
use Workbench\App\Filament\Widgets\StudioOverview;
use Workbench\App\Filament\Widgets\WorkMixChart;

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
 *
 * The middleware stack below is the one Filament writes into a generated
 * panel provider. It is spelled out here because a panel starts with none,
 * and without a session the panel can show the sign-in screen but never hold
 * a sign-in — which is also what the language switcher needs, since the
 * choice has to outlive the request that made it.
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
            ->pages([Dashboard::class])
            ->resources([
                ProjectResource::class,
                ClientResource::class,
            ])
            ->widgets([
                StudioOverview::class,
                DeliveryChart::class,
                WorkMixChart::class,
                RecentWork::class,
            ])
            ->navigationGroups(['Delivery', 'Commercial'])
            /*
             * The collapsed rail is a second layout, not a narrower version of
             * the first one, and it is the layout most likely to be left
             * unstyled. Turning it on here is what makes it possible to see the
             * rail in the package itself rather than only in an application.
             */
            ->sidebarCollapsibleOnDesktop()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(
                MiaTheme::make()
                    ->customizer()
                    ->localeSwitcher()
                    ->loginTagline('Client work, kept in one place.'),
            );
    }
}
