<?php

namespace JohnRivera7\FilamentMia\Support;

use Filament\Facades\Filament;
use Filament\Panel;
use Throwable;

/**
 * The way back to the panel from a page that is not in one.
 *
 * An error page is the one place in a Filament application where the visitor
 * has no navigation, so it has to carry its own. Which panel to return them to
 * is not obvious, though: error views are registered application-wide, and a
 * request that 404s may never have reached a panel at all.
 *
 * Resolved in three steps, most specific first: the panel the request was
 * being served by, then the application's default panel, then the application
 * root. Every step is wrapped, because this code runs while something has
 * already gone wrong — a panel provider that throws must not turn a tidy 404
 * into a blank page, and a multi-tenant panel asked for a URL with no tenant
 * resolved will throw rather than answer.
 */
class PanelExit
{
    public static function panel(): ?Panel
    {
        try {
            return Filament::getCurrentPanel() ?? Filament::getDefaultPanel();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Where "back to the panel" should go.
     *
     * Falls back to the application root, which is always safe to link and is
     * where most applications put the way in.
     */
    public static function url(): string
    {
        $panel = static::panel();

        if ($panel !== null) {
            try {
                if (filled($url = $panel->getUrl())) {
                    return $url;
                }
            } catch (Throwable) {
                // A tenant-scoped panel cannot answer without a tenant.
            }
        }

        return url('/');
    }

    /**
     * Where to send someone whose session has expired.
     *
     * The sign-in screen when the panel has one, which is the whole point of
     * the 419 page: the session is gone, so the panel itself would only bounce
     * them here anyway. Panels with no authentication fall back to the panel.
     */
    public static function loginUrl(): string
    {
        $panel = static::panel();

        if ($panel !== null) {
            try {
                if (filled($url = $panel->getLoginUrl())) {
                    return $url;
                }
            } catch (Throwable) {
                //
            }
        }

        return static::url();
    }

    /**
     * The way back, as the standalone layout wants it.
     *
     * Labelled with the panel's own brand name where there is one, because
     * "Back to Atelier" tells someone looking at a 404 that they are still
     * inside the application they thought they were in, and "Back to the
     * panel" does not.
     *
     * @return array{label: string, url: string, primary: bool}
     */
    public static function backAction(bool $primary = true): array
    {
        $brand = static::brandName();

        return [
            'label' => $brand === null
                ? __('filament-mia::http.actions.back')
                : __('filament-mia::http.actions.back_to', ['name' => $brand]),
            'url' => static::url(),
            'primary' => $primary,
        ];
    }

    /**
     * @return array{label: string, url: string, primary: bool}
     */
    public static function signInAction(bool $primary = true): array
    {
        return [
            'label' => __('filament-mia::http.actions.sign_in'),
            'url' => static::loginUrl(),
            'primary' => $primary,
        ];
    }

    /**
     * The panel's brand name, for the label on the way back.
     *
     * Null when there is no panel to name, in which case the caller uses
     * wording that does not claim there is one.
     */
    public static function brandName(): ?string
    {
        $panel = static::panel();

        if ($panel === null) {
            return null;
        }

        try {
            $name = $panel->getBrandName();
        } catch (Throwable) {
            return null;
        }

        $name = is_string($name) ? $name : strip_tags($name->toHtml());

        return blank($name) ? null : $name;
    }
}
