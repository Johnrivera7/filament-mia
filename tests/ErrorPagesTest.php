<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\View;
use JohnRivera7\FilamentMia\Tests\Fixtures\TestPanelProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The error pages, exercised the way an application meets them: by making a
 * request that fails and reading what came back.
 *
 * Rendering the views directly would prove less than it looks. Laravel rebuilds
 * the whole `errors` view namespace from `config('view.paths')` immediately
 * before it renders — `RegisterErrorViewPaths` — so a test that resolves
 * `errors::404` outside a request is testing a namespace that does not exist
 * yet at that point, and would miss the one thing worth checking: that
 * appending to `view.paths` survives that rebuild.
 */
class ErrorPagesTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), TestPanelProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        // Set here rather than in a test body because the option is read when
        // the provider boots, which has already happened by then.
        $app['config']->set('filament-mia.error_pages', true);

        // With debug on, Laravel renders its own trace page and never reaches
        // an error view at all.
        $app['config']->set('app.debug', false);
    }

    protected function defineRoutes($router): void
    {
        $router->get('/blows-up', fn () => throw new RuntimeException('the database is on fire'));
        $router->get('/refused', fn () => throw new AuthorizationException);
        $router->get('/refused-with-reason', fn () => throw new AuthorizationException('Projects with open invoices cannot be deleted.'));
        $router->get('/stale', fn () => throw new TokenMismatchException);
        $router->get('/teapot', fn () => throw new HttpException(418, 'I am a teapot'));
    }

    #[DataProvider('statuses')]
    public function test_a_failing_request_comes_back_as_a_theme_page(int $status, string $url): void
    {
        $response = $this->get($url);

        $response->assertStatus($status);
        $response->assertSee('<!DOCTYPE html>', false);

        // The element, not the class name: the class name is also in the
        // stylesheet the page carries inline, so matching it would pass on a
        // page that rendered nothing at all.
        $response->assertSee('<main class="mia-standalone-card">', false);
    }

    /**
     * The stylesheet is in the document rather than linked. The compiled theme
     * reads colour properties that only a Filament panel render emits, and the
     * maintenance page is served before the framework can resolve an asset URL
     * at all, so both standalone pages carry their own CSS.
     */
    #[DataProvider('statuses')]
    public function test_the_page_is_self_contained(int $status, string $url): void
    {
        $response = $this->get($url);

        $response->assertSee('.mia-standalone-card {', false);
        $response->assertDontSee('mia.css', false);
    }

    /** Dead ends should not be indexed as if they were the real thing. */
    #[DataProvider('statuses')]
    public function test_the_page_asks_not_to_be_indexed(int $status, string $url): void
    {
        $this->get($url)->assertSee('name="robots" content="noindex, nofollow"', false);
    }

    #[DataProvider('statuses')]
    public function test_the_page_offers_a_way_out(int $status, string $url): void
    {
        $this->assertMatchesRegularExpression(
            '/<a\s+href="[^"]+"\s+class="mia-standalone-(button|link)"/',
            $this->get($url)->getContent(),
        );
    }

    /**
     * Four different walls, four different things to say. A 419 has not lost
     * the visitor to a missing address, and a 404 is not a failure.
     */
    public function test_each_status_says_something_of_its_own(): void
    {
        $headings = [];

        foreach (static::statuses() as [$status, $url]) {
            preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $this->get($url)->getContent(), $matches);

            $headings[$status] = trim($matches[1] ?? '');
        }

        $this->assertCount(4, array_filter($headings));
        $this->assertSame($headings, array_unique($headings));
    }

    /**
     * A 419 means the session is gone, so the panel would only bounce the
     * visitor to the sign-in screen. The page sends them there itself.
     */
    public function test_the_session_page_points_at_the_sign_in_screen(): void
    {
        $this->get('/stale')->assertSee('/testing/login', false);
    }

    /**
     * Every other page sends them back to the panel, named, so that a 404
     * still reads as being inside the application they thought they were in.
     */
    public function test_the_other_pages_point_back_at_the_panel(): void
    {
        foreach (['/nowhere', '/refused', '/blows-up'] as $url) {
            $this->get($url)->assertSee('Back to Studio');
        }
    }

    /**
     * An `AuthorizationException` often carries a message written for the
     * person who was refused, and it is worth more than the generic line.
     */
    public function test_a_refusal_reason_replaces_the_generic_line(): void
    {
        $this->get('/refused-with-reason')
            ->assertSee('Projects with open invoices cannot be deleted.');
    }

    /**
     * Laravel's own placeholder message tells the reader nothing the heading
     * does not, so the page keeps its own wording.
     */
    public function test_a_placeholder_reason_does_not(): void
    {
        $response = $this->get('/refused');

        $response->assertDontSee('This action is unauthorized.');
        $response->assertSee('check your permissions');
    }

    /** The one thing that must never leak from a 500 is why it broke. */
    public function test_the_server_error_page_gives_nothing_away(): void
    {
        $response = $this->get('/blows-up');

        $response->assertDontSee('the database is on fire');
        $response->assertDontSee('RuntimeException');
        $response->assertDontSee(basename(__FILE__));
    }

    /**
     * What may reach the reader is the request identifier the infrastructure
     * already set, which is not derived from the failure at all. It gives them
     * something to quote and an operator something to search for.
     */
    public function test_the_server_error_page_shows_a_request_identifier_when_there_is_one(): void
    {
        $this->get('/blows-up')
            ->assertDontSee('<p class="mia-standalone-reference">', false);

        $this->get('/blows-up', ['X-Request-Id' => 'req_01JQ8Z4T'])
            ->assertSee('req_01JQ8Z4T');
    }

    /**
     * The theme ships four pages and answers for four statuses. Anything else
     * keeps falling through to whatever the application or the framework
     * already does, rather than the theme swallowing statuses it has no words
     * for.
     */
    public function test_a_status_the_theme_does_not_ship_is_left_alone(): void
    {
        $response = $this->get('/teapot');

        $response->assertStatus(418);
        $response->assertDontSee('mia-standalone-card', false);
    }

    /**
     * The whole reason the path is appended to `view.paths` rather than
     * registered against the `errors` namespace: Laravel rebuilds that
     * namespace from `view.paths` before rendering, application paths first,
     * so an application's own view stays in front of the theme's.
     */
    public function test_an_application_view_still_wins(): void
    {
        $applicationPath = config('view.paths')[0];

        @mkdir("{$applicationPath}/errors", 0777, true);
        file_put_contents("{$applicationPath}/errors/404.blade.php", 'the application view');

        try {
            $this->get('/nowhere')->assertSee('the application view');
        } finally {
            unlink("{$applicationPath}/errors/404.blade.php");
        }
    }

    public function test_the_pages_are_translated(): void
    {
        app()->setLocale('es');

        $response = $this->get('/nowhere');

        $response->assertSee('No hay nada en esta dirección', false);
        $response->assertSee('lang="es"', false);
    }

    /**
     * The pages are reachable under the theme's own namespace too, for an
     * application that wants one of them at one route without handing the
     * theme the whole `errors` namespace.
     */
    #[DataProvider('codes')]
    public function test_the_pages_are_addressable_under_the_theme_namespace(string $code): void
    {
        $this->assertTrue(View::exists("filament-mia::http.errors.{$code}"));
    }

    /** @return array<string, array{int, string}> */
    public static function statuses(): array
    {
        return [
            '403' => [403, '/refused'],
            '404' => [404, '/nowhere'],
            '419' => [419, '/stale'],
            '500' => [500, '/blows-up'],
        ];
    }

    /** @return array<array<string>> */
    public static function codes(): array
    {
        return [['403'], ['404'], ['419'], ['500']];
    }
}
