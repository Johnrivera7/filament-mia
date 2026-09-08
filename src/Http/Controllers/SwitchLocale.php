<?php

namespace JohnRivera7\FilamentMia\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Support\LocaleStore;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Stores a language choice and returns the visitor to where they were.
 *
 * A plain route rather than a Livewire action: the whole document has to be
 * re-rendered in the new language anyway, the preference has to be written as
 * a cookie on a real response, and this way the switcher keeps working on a
 * page whose Livewire component has already errored.
 *
 * The panel is part of the URL because the accepted languages are a per-panel
 * setting, and because an application may run the theme on more than one panel
 * with different lists.
 */
class SwitchLocale
{
    public function __invoke(Request $request, string $panel, string $locale): RedirectResponse
    {
        $panel = Filament::getPanel($panel, isStrict: false);

        if (! $panel?->hasPlugin('mia-theme')) {
            throw new NotFoundHttpException;
        }

        /** @var MiaTheme $plugin */
        $plugin = $panel->getPlugin('mia-theme');

        if (! $plugin->hasLocale($locale)) {
            throw new NotFoundHttpException;
        }

        LocaleStore::queue($locale);

        return redirect()->back(fallback: $panel->getUrl());
    }
}
