{{--
    Reachable two ways, and both matter.

    As `errors::404`, which is what Laravel renders for a 404, once the theme's
    view path has been added to `view.paths` — see the `error_pages` option in
    `config/filament-mia.php`. That registration deliberately *appends*, so an
    application that has its own `resources/views/errors/404.blade.php` keeps
    it: this file only ever fills a gap.

    And as `filament-mia::http.errors.404`, for an application that wants the
    theme's page at one route without handing it the whole namespace.
--}}
@include('filament-mia::http.layout', [
    'code' => 404,
    'title' => __('filament-mia::http.errors.404.title'),
    'heading' => __('filament-mia::http.errors.404.heading'),
    'body' => __('filament-mia::http.errors.404.body'),
    'actions' => [\JohnRivera7\FilamentMia\Support\PanelExit::backAction()],
])
