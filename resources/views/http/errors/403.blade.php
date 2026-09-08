{{--
    See the note in `404.blade.php` for how this view is resolved.

    Laravel passes the exception in, and an `AuthorizationException` often
    carries a message written for the person who was refused. It is shown in
    place of the generic line when there is one, because "you cannot delete a
    project with open invoices" is worth more than "you do not have access".
--}}
@php
    // Guarded, because the view is also renderable on its own — from a route
    // an application points at it, or from a test — with no exception to pass.
    $reason = ($exception ?? null)?->getMessage();

    // Symfony fills the message with the reason phrase when nothing was
    // given, and "This action is unauthorized." is Laravel's own placeholder.
    // Neither tells the reader anything the heading does not.
    $isGeneric = in_array($reason, [
        '',
        'Forbidden',
        'This action is unauthorized.',
        __('filament-mia::http.errors.403.heading'),
    ], true);
@endphp

@include('filament-mia::http.layout', [
    'code' => 403,
    'title' => __('filament-mia::http.errors.403.title'),
    'heading' => __('filament-mia::http.errors.403.heading'),
    'body' => $isGeneric ? __('filament-mia::http.errors.403.body') : $reason,
    'actions' => [\JohnRivera7\FilamentMia\Support\PanelExit::backAction()],
])
