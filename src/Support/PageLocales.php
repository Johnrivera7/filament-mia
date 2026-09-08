<?php

namespace JohnRivera7\FilamentMia\Support;

use Filament\Panel;
use JohnRivera7\FilamentMia\Http\Controllers\SwitchLocale;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;

/**
 * The languages the public page may be read in.
 *
 * The page is rendered outside any panel, so there is no current panel to ask
 * which languages were configured. It asks the panel that switched the builder
 * on instead — the one {@see PageBuilderPanel} already resolves for the
 * preview's guard — because the accepted languages are a per-panel setting and
 * that is the panel this page belongs to.
 *
 * Nothing is stored or decided here. The choice is written by
 * {@see SwitchLocale} into the same cookie the panel's own switcher writes,
 * which is what makes the public page work for a visitor who has never signed
 * in: the preference is a cookie on the browser rather than a column on a
 * user, so there is no session to belong to. It also means the two switchers
 * agree — someone who picks Español on the public page arrives at the sign-in
 * screen in Español.
 */
final class PageLocales
{
    /**
     * The languages on offer, as `code => label`.
     *
     * Empty when the builder is off, when no panel answers for it, or when the
     * language switcher was never turned on. Callers render nothing for any of
     * those: a switcher with one option is not a switcher, and the theme
     * refuses to configure one, so an empty list is the only "single language"
     * state that exists.
     *
     * @return array<string, string>
     */
    public static function available(): array
    {
        return self::plugin()?->getLocales() ?? [];
    }

    /**
     * Where a language choice is made.
     *
     * The panel's own switch route, reused verbatim. It already validates the
     * code against the panel's list, writes the cookie and returns the visitor
     * to where they were, and it is registered under `web` with no
     * authentication — the sign-in screen needed that long before the public
     * page did.
     */
    public static function switchUrl(string $locale): ?string
    {
        $panel = self::panel();

        if ($panel === null) {
            return null;
        }

        return route('filament-mia.locale', [
            'panel' => $panel->getId(),
            'locale' => $locale,
        ]);
    }

    protected static function panel(): ?Panel
    {
        return PageBuilderPanel::resolve();
    }

    protected static function plugin(): ?MiaTheme
    {
        $plugin = self::panel()?->getPlugin('mia-theme');

        return $plugin instanceof MiaTheme ? $plugin : null;
    }
}
