<?php

namespace JohnRivera7\FilamentMia\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Support\LocaleStore;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the stored language choice to the request.
 *
 * Only added to a panel when the switcher is turned on, and only ever inside
 * that panel: an application that manages its own locale keeps control of
 * every other route, and of every panel where the switcher is off.
 *
 * Registered as persistent middleware, so Livewire's own requests resolve the
 * same locale as the page that issued them. Without that, a table would
 * paginate back into the application's default language.
 */
class ApplyLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = LocaleStore::read($request);

        if ($locale !== null) {
            $panel = Filament::getCurrentPanel();

            /** @var MiaTheme|null $plugin */
            $plugin = $panel?->hasPlugin('mia-theme') ? $panel->getPlugin('mia-theme') : null;

            // Checked against the panel's own list rather than trusted: the
            // cookie is shared by every panel in the application, and a value
            // one panel offers may not be one this panel has translations for.
            if ($plugin?->hasLocale($locale)) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
