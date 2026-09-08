{{--
    See the note in `404.blade.php` for how this view is resolved.

    The one error page whose way out is not the panel. A 419 means the session
    is gone, so the panel would only bounce the visitor to the sign-in screen;
    this sends them there directly.
--}}
@include('filament-mia::http.layout', [
    'code' => 419,
    'title' => __('filament-mia::http.errors.419.title'),
    'heading' => __('filament-mia::http.errors.419.heading'),
    'body' => __('filament-mia::http.errors.419.body'),
    'actions' => [\JohnRivera7\FilamentMia\Support\PanelExit::signInAction()],
])
