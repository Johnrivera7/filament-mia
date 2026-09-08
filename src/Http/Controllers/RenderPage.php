<?php

namespace JohnRivera7\FilamentMia\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use JohnRivera7\FilamentMia\PageBuilder\PageBuilderPanel;
use JohnRivera7\FilamentMia\PageBuilder\PageContent;
use JohnRivera7\FilamentMia\PageBuilder\PagePayload;
use JohnRivera7\FilamentMia\Pages\PageBuilder;

/**
 * Serves a page built with the page builder.
 *
 * Two entry points over the same view: the published payload for visitors, and
 * the draft for whoever is editing it. The draft is told not to be indexed, so
 * an unfinished page cannot end up in a search result.
 */
class RenderPage
{
    public function __construct(private readonly PageContent $content) {}

    public function __invoke(): View
    {
        return $this->render($this->content->published(), draft: false);
    }

    /**
     * The draft, for whoever may open the builder.
     *
     * The guard is written out here rather than delegated to the `auth`
     * middleware because that middleware sends a guest to a route named
     * `login`, and a Filament application often does not define one — signing
     * in belongs to the panel. Left to the middleware, an unauthenticated
     * visit would raise a routing exception instead of asking for credentials.
     */
    public function preview(): View|RedirectResponse
    {
        $panel = PageBuilderPanel::resolve();

        abort_if($panel === null, 404);

        // The page's own `canAccess()` reads the plugin through the current
        // panel, which nothing has set this far outside one.
        Filament::setCurrentPanel($panel);

        if (! Filament::auth()->check()) {
            return redirect()->guest(PageBuilderPanel::signInUrl($panel));
        }

        abort_unless(PageBuilder::canAccess(), 403);

        return $this->render($this->content->draft(), draft: true);
    }

    private function render(PagePayload $payload, bool $draft): View
    {
        return view('filament-mia::page-builder.layout', [
            'blocks' => $payload->blocks(),
            'draft' => $draft,
        ]);
    }
}
