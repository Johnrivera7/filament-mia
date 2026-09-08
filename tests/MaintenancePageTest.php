<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Support\Facades\Artisan;
use JohnRivera7\FilamentMia\Tests\Fixtures\TestPanelProvider;

/**
 * The maintenance page, driven through the command that actually produces it.
 *
 * `php artisan down --render=…` renders the view once and stores the HTML as a
 * string in `storage/framework/down`. Every request that arrives while the
 * application is down is then answered by `storage/framework/maintenance.php`,
 * which is required from `public/index.php` *before* Composer's autoloader —
 * so at the moment this page is served there is no container, no config, no
 * session, no database and no view factory.
 *
 * That is what these tests are about. Rendering the view would only prove it
 * compiles; reading what ends up in the payload is what proves the page can
 * still be served once everything it was rendered with is gone.
 */
class MaintenancePageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), TestPanelProvider::class];
    }

    protected function tearDown(): void
    {
        @unlink(storage_path('framework/down'));
        @unlink(storage_path('framework/maintenance.php'));

        parent::tearDown();
    }

    public function test_the_command_prerenders_the_page_into_the_down_file(): void
    {
        $template = $this->prerender();

        $this->assertStringContainsString('<!DOCTYPE html>', $template);
        $this->assertStringContainsString('<main class="mia-standalone-card">', $template);
        $this->assertStringContainsString('Back shortly', $template);
    }

    /**
     * The one thing that would break the page at the moment it is needed: a
     * reference to anything the application has to be up to serve.
     */
    public function test_the_prerendered_page_needs_nothing_from_the_application(): void
    {
        $template = $this->prerender();

        // The stylesheet is in the document, not linked. There is no asset URL
        // helper at serve time, and no panel render to emit the theme's colour
        // properties either.
        $this->assertStringContainsString('.mia-standalone-card {', $template);
        $this->assertStringNotContainsString('mia.css', $template);
        $this->assertStringNotContainsString('/css/johnrivera7', $template);

        // No Livewire, no Alpine, no Vite, no CSRF token — all of them need a
        // booted framework, and two of them need a session.
        foreach (['livewire', 'alpine', '@vite', 'csrf-token', 'wire:'] as $needle) {
            $this->assertStringNotContainsString($needle, strtolower($template));
        }

        /*
         * Every URL in the page has to be absolute. The template is served for
         * whatever address the visitor happened to hit, so a relative link
         * would resolve against a path that does not exist.
         */
        preg_match_all('/href="([^"]+)"/', $template, $matches);

        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $href) {
            $this->assertMatchesRegularExpression('#^https?://#', $href, "Relative href: {$href}");
        }
    }

    /**
     * Nothing in the page may come from the session, the signed-in user or the
     * appearance settings on disk, so light and dark are decided in the
     * browser: `prefers-color-scheme` in the stylesheet, refined by the choice
     * Filament keeps in `localStorage`, which survives the server being down.
     */
    public function test_dark_mode_is_resolved_in_the_browser(): void
    {
        $template = $this->prerender();

        $this->assertStringContainsString('@media (prefers-color-scheme: dark)', $template);
        $this->assertStringContainsString("localStorage.getItem('theme')", $template);

        // The stylesheet answers on its own, so the script is an upgrade
        // rather than a dependency: no JavaScript still means a themed page.
        $this->assertStringContainsString(":root[data-mia-scheme='dark']", $template);
    }

    /**
     * The palette, type and shape come from the config file, read while the
     * framework was still up. That is the trade for surviving the serve: a
     * look saved in the appearance page belongs to one panel and is not here.
     */
    public function test_the_page_carries_the_configured_palette(): void
    {
        config()->set('filament-mia.colors.accent', '#4C6BB3');

        $template = $this->prerender();

        // The accent as an OKLCH literal, since there is no Filament runtime
        // on this page to emit the ramp as custom properties.
        $this->assertMatchesRegularExpression('/--primary-600:\s*oklch\([^)]*26[0-9.]*\)/', $template);
    }

    public function test_a_retry_time_becomes_a_sentence(): void
    {
        $this->assertStringContainsString(
            'try again in about 10 minutes',
            $this->prerender(['--retry' => 600]),
        );
    }

    /** `--retry` also takes a date, which is no use in a sentence. */
    public function test_a_retry_date_falls_back_to_the_plain_wording(): void
    {
        $template = $this->prerender(['--retry' => 'tomorrow 09:00']);

        $this->assertStringContainsString('try again in a few minutes', $template);
    }

    public function test_the_page_is_translated(): void
    {
        app()->setLocale('es');

        $template = $this->prerender();

        $this->assertStringContainsString('Volvemos enseguida', $template);
        $this->assertStringContainsString('lang="es"', $template);
    }

    /** A maintenance page is not the version of the site to index. */
    public function test_the_page_asks_not_to_be_indexed(): void
    {
        $this->assertStringContainsString(
            'name="robots" content="noindex, nofollow"',
            $this->prerender(),
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function prerender(array $options = []): string
    {
        Artisan::call('down', [
            '--render' => 'filament-mia::maintenance',
            ...$options,
        ]);

        $payload = json_decode((string) file_get_contents(storage_path('framework/down')), true);

        $this->assertIsArray($payload);
        $this->assertArrayHasKey('template', $payload);

        return (string) $payload['template'];
    }
}
