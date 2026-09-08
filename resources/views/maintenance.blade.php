{{--
    The maintenance page.

        php artisan down --render="filament-mia::maintenance"

    This view is never served. Laravel renders it once, at the moment that
    command runs, and stores the resulting HTML as a string inside
    `storage/framework/down`; every request that arrives while the application
    is down is then answered by `storage/framework/maintenance.php`, which
    echoes that string and exits. That happens in `public/index.php` before
    Composer's autoloader is required, so at the moment this page is *served*
    there is no container, no configuration, no session, no database and no
    view factory — there is a web server, a JSON file and an `echo`.

    Which is why the layout it uses inlines its stylesheet instead of linking
    one. A `<link>` to the published theme would be an asset URL this page
    cannot build, pointing at a stylesheet whose colours only a Filament panel
    render emits, on a page that has neither. See `Support\StandaloneSheet`.

    The same reasoning rules out anything else the theme normally reads: the
    appearance settings on disk, the panel registry, `auth()`. Everything here
    comes from `config/filament-mia.php`, and it is read at prerender time
    while the framework is still up. Re-run the command after changing the
    config file or the page will keep showing the old one.

    Dark mode comes from `prefers-color-scheme`, refined by the light/dark
    choice Filament keeps in `localStorage` — client-side, and therefore still
    readable when the server is not answering.
--}}
@php
    use Carbon\CarbonInterval;

    /*
     * `--retry` is handed to this view as `$retryAfter`. It doubles as the
     * `Retry-After` header, so it is whatever the operator typed: a number of
     * seconds, a date, or nothing. Only a number can be turned into a sentence
     * a reader gets anything out of.
     */
    $retryAfter ??= null;

    $duration = (is_numeric($retryAfter) && $retryAfter > 0)
        ? CarbonInterval::seconds((int) $retryAfter)->cascade()->forHumans()
        : null;
@endphp

@include('filament-mia::http.layout', [
    // A word rather than 503. The status is set by `--status` after this page
    // has already been rendered, so a number printed here could be a lie.
    'code' => __('filament-mia::http.maintenance.eyebrow'),
    'title' => __('filament-mia::http.maintenance.title'),
    'heading' => __('filament-mia::http.maintenance.heading'),
    'body' => $duration === null
        ? __('filament-mia::http.maintenance.body')
        : __('filament-mia::http.maintenance.body_retry', ['duration' => $duration]),
    'actions' => [
        [
            // An absolute URL baked in from `app.url`, because a relative one
            // would be resolved against whatever address the visitor happened
            // to hit, and because there is nothing here that could build one.
            'label' => __('filament-mia::http.maintenance.retry'),
            'url' => url('/'),
            'primary' => true,
        ],
    ],
])
