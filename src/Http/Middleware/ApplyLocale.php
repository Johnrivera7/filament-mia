<?php

namespace JohnRivera7\FilamentMia\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;
use JohnRivera7\FilamentMia\Support\LocaleStore;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the stored language choice to the request.
 *
 * Added to a panel when the switcher is turned on, and to the theme's own
 * public page — never to anything else: an application that manages its own
 * locale keeps control of every other route, and of every panel where the
 * switcher is off.
 *
 * Registered as persistent middleware on a panel, so Livewire's own requests
 * resolve the same locale as the page that issued them. Without that, a table
 * would paginate back into the application's default language.
 */
class ApplyLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = LocaleStore::read($request);

        if ($locale !== null) {
            // The public page is served with no panel around it, so there is
            // no current one to read the list from. It belongs to whichever
            // panel switched the builder on, which is the panel that decided
            // which languages exist in the first place.
            $panel = Filament::getCurrentPanel() ?? PageBuilderPanel::resolve();

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
