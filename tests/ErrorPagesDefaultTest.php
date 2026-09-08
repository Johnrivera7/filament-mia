<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Foundation\Exceptions\RegisterErrorViewPaths;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The error pages are opt-in, and the default has to stay a default.
 *
 * Error views are application-wide rather than panel-scoped, so installing a
 * theme must not quietly restyle every error in an application — including the
 * ones raised by routes that have nothing to do with a panel.
 *
 * Its own class because the option is read when the provider boots, so proving
 * it is off means building an application that never set it.
 */
class ErrorPagesDefaultTest extends TestCase
{
    public function test_the_option_is_off_unless_asked_for(): void
    {
        $this->assertFalse(config('filament-mia.error_pages'));
    }

    public function test_the_theme_does_not_claim_the_errors_namespace(): void
    {
        (new RegisterErrorViewPaths)();

        /*
         * Laravel ships its own 404, so the check that matters is not whether
         * the view resolves but which file answered for it.
         */
        $this->assertStringNotContainsString(
            'mia-standalone',
            view('errors::404', ['exception' => new HttpException(404)])->render(),
        );
    }

    public function test_the_views_are_still_reachable_under_the_theme_namespace(): void
    {
        $this->assertTrue(View::exists('filament-mia::http.errors.404'));
    }
}
