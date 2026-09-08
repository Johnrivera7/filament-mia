<?php

namespace JohnRivera7\FilamentMia\PageBuilder;

use Filament\Facades\Filament;
use Filament\Panel;
use JohnRivera7\FilamentMia\MiaTheme;

/**
 * Finds the panel the page builder was switched on in.
 *
 * The public page and its preview are rendered outside any panel, so there is
 * no current panel to ask. Both still need one: the preview has to know which
 * guard to check and where to send someone who is not signed in, and the
 * public route has to know whether the feature was asked for at all.
 *
 * The first panel that enabled it wins. A second panel asking for the builder
 * as well is not an error — both write to the same page — it simply does not
 * get a second public address.
 */
final class PageBuilderPanel
{
    public static function resolve(): ?Panel
    {
        foreach (Filament::getPanels() as $panel) {
            if (! $panel->hasPlugin('mia-theme')) {
                continue;
            }

            $plugin = $panel->getPlugin('mia-theme');

            if ($plugin instanceof MiaTheme && $plugin->hasPageBuilder()) {
                return $panel;
            }
        }

        return null;
    }

    /**
     * Where a visitor who is not signed in should be sent from the preview.
     *
     * The panel's own sign-in screen when it has one, and the panel itself
     * otherwise — a panel with no `login()` is one that authenticates
     * somewhere else, and its own address is the closest thing to a way in
     * that the theme can know about.
     */
    public static function signInUrl(Panel $panel): string
    {
        return $panel->getLoginUrl() ?? $panel->getUrl() ?? url('/');
    }
}
