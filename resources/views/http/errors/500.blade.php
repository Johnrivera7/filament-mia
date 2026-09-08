{{--
    See the note in `404.blade.php` for how this view is resolved.

    Nothing from the exception is shown. A 500 page is served to whoever
    happened to be using the panel, and the one thing that must not leak from
    it is why it broke; `config('app.debug')` decides that, and when debug is
    on Laravel never reaches this view in the first place.

    What is shown, when the request carries one, is the request identifier most
    load balancers and log pipelines already set. It gives the reader something
    to quote and whoever reads the logs something to search for, and it is not
    derived from the failure at all.
--}}
@include('filament-mia::http.layout', [
    'code' => 500,
    'title' => __('filament-mia::http.errors.500.title'),
    'heading' => __('filament-mia::http.errors.500.heading'),
    'body' => __('filament-mia::http.errors.500.body'),
    'actions' => [\JohnRivera7\FilamentMia\Support\PanelExit::backAction()],
    'reference' => request()->header('X-Request-Id'),
])
