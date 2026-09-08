{{--
    The shared shell for every page the theme draws with no panel around it:
    the four error pages and the maintenance page.

    Self-contained on purpose. The stylesheet is inlined rather than linked,
    because the compiled theme reads colour properties that only a Filament
    panel render emits, and because the maintenance page is served out of
    `storage/framework/maintenance.php` before the framework — and therefore
    the asset URL helper — exists. See `Support\StandaloneSheet`.

    Always addressed as `filament-mia::http.layout`, never as `http.layout`.
    The error views are reachable through Laravel's `errors::` namespace, whose
    hint paths an application can add to, so an unnamespaced include here could
    be answered by a file the application published rather than by this one.

    Expected data:

      code       Status code, or null to omit the eyebrow line entirely.
      title      Document title.
      heading    The one line that says what happened.
      body       A sentence or two on what to do about it.
      actions    Ordered list of ['label' => …, 'url' => …, 'primary' => bool].
      reference  An opaque identifier for whoever reads the logs, or null.
      refresh    Seconds until the page reloads itself, or null.
--}}
@php
    use JohnRivera7\FilamentMia\Support\StandaloneSheet;

    $sheet = StandaloneSheet::fromConfig();

    $code ??= null;
    $actions ??= [];
    $reference ??= null;
    $refresh ??= null;

    $direction = __('filament-panels::layout.direction');
    $direction = in_array($direction, ['ltr', 'rtl'], true) ? $direction : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $direction }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--
            These pages are dead ends by definition: a missing address, a
            refused one, or an application that is not answering. None of them
            should be indexed or cached as if they were the real thing.
        --}}
        <meta name="robots" content="noindex, nofollow">

        @if ($refresh !== null)
            <meta http-equiv="refresh" content="{{ $refresh }}">
        @endif

        <title>{{ $title }}</title>

        {{--
            Before the stylesheet, so the mode is decided before the first
            paint and the card cannot flash light on its way to dark. The
            stylesheet resolves `prefers-color-scheme` by itself, so this only
            ever refines the answer.
        --}}
        <script>{!! $sheet->schemeScript() !!}</script>

        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="stylesheet" href="{{ $sheet->fontUrl() }}">

        <style>{!! $sheet->css() !!}</style>
    </head>

    <body class="mia-standalone">
        <main class="mia-standalone-card">
            {{-- Decorative: the words below already say what happened. --}}
            <div class="mia-standalone-mark" aria-hidden="true"></div>

            @if (filled($code))
                <p class="mia-standalone-code">{{ $code }}</p>
            @endif

            <h1 class="mia-standalone-heading">{{ $heading }}</h1>

            <p class="mia-standalone-body">{{ $body }}</p>

            @if ($actions !== [])
                <hr class="mia-standalone-rule">

                <div class="mia-standalone-actions">
                    @foreach ($actions as $action)
                        <a
                            href="{{ $action['url'] }}"
                            class="{{ ($action['primary'] ?? false) ? 'mia-standalone-button' : 'mia-standalone-link' }}"
                        >{{ $action['label'] }}</a>
                    @endforeach
                </div>
            @endif

            @if (filled($reference))
                <p class="mia-standalone-reference">{{ $reference }}</p>
            @endif
        </main>
    </body>
</html>
