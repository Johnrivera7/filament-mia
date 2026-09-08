<?php

namespace JohnRivera7\FilamentMia\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Where the visitor's language choice lives.
 *
 * A long-lived cookie rather than the session, for the same reason Filament
 * keeps the light/dark choice in `localStorage`: it is a display preference of
 * the browser, not of the sign-in. It has to survive a session expiring, a
 * sign-out, and being read on the sign-in screen, where there is no
 * authenticated user to hang it off.
 *
 * The cookie is written by Laravel's cookie jar, so it is encrypted and
 * `HttpOnly` like every other cookie the framework sets. Applications that
 * would rather keep the preference on the user record can ignore the switcher
 * and set the locale in their own middleware.
 */
class LocaleStore
{
    public const NAME = 'filament_mia_locale';

    public static function read(Request $request): ?string
    {
        $value = $request->cookie(static::NAME);

        return is_string($value) && filled($value) ? $value : null;
    }

    public static function queue(string $locale): void
    {
        Cookie::queue(Cookie::forever(static::NAME, $locale));
    }
}
