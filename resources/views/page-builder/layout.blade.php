{{--
    The public page the block builder composes.

    Self-contained on purpose, exactly as the error pages are. The stylesheet
    is inlined rather than linked, because the compiled theme reads colour
    properties that only a Filament panel render emits, and because a theme
    installed with Composer cannot ask the application to run a Tailwind build.
    See `Support\PageSheet`.

    Blocks are rendered in the order the builder left them. Each one is handed
    to `page-builder.section`, which wraps it in the surface it was assigned
    and includes the partial for its type — so adding a section to the
    catalogue means adding a block class and a partial, and nothing else.

    Expected data:

      blocks   Ordered list of ['type' => …, 'data' => [...]].
      draft    Whether this is the unpublished draft rather than the live page.
--}}
@php
    use JohnRivera7\FilamentMia\Support\PageSheet;

    $sheet = PageSheet::fromConfig();

    $draft ??= false;

    $direction = __('filament-panels::layout.direction');
    $direction = in_array($direction, ['ltr', 'rtl'], true) ? $direction : 'ltr';

    // The main landmark opens on the first block that is content rather than
    // page furniture, wherever the builder happened to put the navigation bar.
    $mainIndex = array_key_first(array_filter(
        $blocks,
        fn (array $block): bool => $block['type'] !== 'navigation',
    ));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $direction }}" class="mia-page-html">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- An unfinished page must not end up in a search result. --}}
        @if ($draft)
            <meta name="robots" content="noindex, nofollow">
        @endif

        <title>{{ config('app.name') }}</title>

        {{--
            Before the stylesheet, so the mode is decided before the first
            paint and a visitor who chose dark never sees a flash of cream.
            The stylesheet resolves `prefers-color-scheme` by itself, so this
            only ever refines the answer.
        --}}
        <script>{!! $sheet->schemeScript() !!}</script>

        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="stylesheet" href="{{ $sheet->fontUrl() }}">

        <style>{!! $sheet->css() !!}</style>
    </head>

    <body class="mia-page">
        <a href="#mia-page-content" class="mia-page-skip">{{ __('filament-mia::page-builder.page.skip') }}</a>

        @if ($draft)
            <p class="mia-page-notice">{{ __('filament-mia::page-builder.page.draft_notice') }}</p>
        @endif

        @forelse ($blocks as $index => $block)
            @include('filament-mia::page-builder.section', [
                'type' => $block['type'],
                'data' => $block['data'],
                'main' => $index === $mainIndex,
            ])
        @empty
            <main id="mia-page-content" class="mia-page-section">
                <div class="mia-page-shell mia-page-centred mia-page-measure">
                    <h1 class="mia-page-h2">{{ __('filament-mia::page-builder.page.empty_heading') }}</h1>
                    <p class="mia-page-lead mia-page-stack--sm">{{ __('filament-mia::page-builder.page.empty_body') }}</p>
                </div>
            </main>
        @endforelse

        {{--
            The light/dark switch, when a navigation block asked for one.
            Progressive: the button is hidden until this has run, so a visitor
            without JavaScript is never shown a dead control.

            Written against `localStorage.theme`, the key Filament itself uses,
            so a visitor who chose dark in the panel arrives on a dark page.
        --}}
        <script>
            (function () {
                var root = document.documentElement;
                var media = window.matchMedia('(prefers-color-scheme: dark)');
                var buttons = document.querySelectorAll('[data-mia-scheme-toggle]');

                if (buttons.length === 0) {
                    return;
                }

                function current() {
                    return root.dataset.miaScheme || (media.matches ? 'dark' : 'light');
                }

                function label(button) {
                    var next = current() === 'dark'
                        ? @json(__('filament-mia::page-builder.page.switch_to_light'))
                        : @json(__('filament-mia::page-builder.page.switch_to_dark'));

                    button.setAttribute('aria-label', next);
                    button.setAttribute('title', next);
                }

                buttons.forEach(function (button) {
                    button.hidden = false;
                    label(button);

                    button.addEventListener('click', function () {
                        var next = current() === 'dark' ? 'light' : 'dark';
                        root.dataset.miaScheme = next;

                        try {
                            localStorage.setItem('theme', next);
                        } catch (error) {
                            /* Nothing to persist to; the change still applies for this visit. */
                        }

                        buttons.forEach(label);
                    });
                });
            })();
        </script>
    </body>
</html>
