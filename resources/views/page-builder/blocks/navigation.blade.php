@php
    use Illuminate\Support\Facades\Storage;
    use JohnRivera7\FilamentMia\Support\PageLocales;

    /** @var array<string, mixed> $data */

    $links = array_values(array_filter(
        is_array($data['links'] ?? null) ? $data['links'] : [],
        fn ($link): bool => is_array($link) && filled($link['label'] ?? null) && filled($link['url'] ?? null),
    ));

    $logo = $data['logo'] ?? null;
    $logo = is_array($logo) ? (array_values(array_filter($logo))[0] ?? null) : $logo;

    $brand = filled($data['brand'] ?? null) ? $data['brand'] : config('app.name');
    $actions = $data['actions'] ?? [];

    // Nothing to switch between unless the panel was given a list, so the
    // control disappears rather than offering a choice of one.
    $locales = ($data['locale_switch'] ?? true) ? PageLocales::available() : [];
    $locales = count($locales) > 1 ? $locales : [];
@endphp

<div class="mia-page-shell mia-page-nav-bar">
    <a href="{{ url('/') }}" class="mia-page-brand">
        @if (filled($logo))
            <img src="{{ Storage::disk('public')->url($logo) }}" alt="">
        @endif

        <span>{{ $brand }}</span>
    </a>

    <div class="mia-page-nav-side">
        @if ($links !== [])
            <nav aria-label="{{ __('filament-mia::page-builder.page.sections') }}" class="mia-page-nav-links">
                @foreach ($links as $link)
                    <a href="{{ $link['url'] }}" class="mia-page-nav-link">{{ $link['label'] }}</a>
                @endforeach
            </nav>
        @endif

        @if ($locales !== [])
            {{--
                A native disclosure, like the mobile menu: the choice is a set
                of links, so it needs no script to open and none to work. Every
                language stays named on screen rather than being cycled
                through, which is the point when the visitor cannot read the
                one the page is currently in.
            --}}
            <details class="mia-page-locale">
                <summary class="mia-page-icon-btn" aria-label="{{ __('filament-mia::page-builder.page.language') }}" title="{{ __('filament-mia::page-builder.page.language') }}">
                    @svg('heroicon-o-language')
                </summary>

                <div class="mia-page-locale-panel">
                    @foreach ($locales as $code => $label)
                        <a
                            href="{{ PageLocales::switchUrl($code) }}"
                            class="mia-page-locale-option"
                            @if ($code === app()->getLocale()) aria-current="true" @endif
                        >{{ $label }}</a>
                    @endforeach
                </div>
            </details>
        @endif

        @if ($data['scheme_toggle'] ?? true)
            {{--
                Hidden until the script in the layout has run, so a visitor
                without JavaScript is never shown a dead control.
            --}}
            <button type="button" class="mia-page-icon-btn" data-mia-scheme-toggle hidden>
                <span class="mia-page-light-only">@svg('heroicon-o-moon')</span>
                <span class="mia-page-dark-only">@svg('heroicon-o-sun')</span>
            </button>
        @endif

        <div class="mia-page-nav-actions">
            @include('filament-mia::page-builder.parts.actions', ['actions' => $actions, 'align' => 'start'])
        </div>

        @if ($links !== [] || filled($actions))
            {{--
                A native disclosure rather than a scripted drawer: it opens
                without JavaScript, and the browser handles the expanded state
                for assistive technology.
            --}}
            <details class="mia-page-menu">
                <summary class="mia-page-icon-btn" aria-label="{{ __('filament-mia::page-builder.page.menu') }}">
                    <span class="mia-page-menu-open">@svg('heroicon-o-bars-3')</span>
                    <span class="mia-page-menu-close">@svg('heroicon-o-x-mark')</span>
                </summary>

                <div class="mia-page-menu-panel">
                    <div class="mia-page-shell mia-page-menu-list">
                        @foreach ($links as $link)
                            <a href="{{ $link['url'] }}" class="mia-page-nav-link">{{ $link['label'] }}</a>
                        @endforeach

                        <div class="mia-page-menu-actions">
                            @include('filament-mia::page-builder.parts.actions', ['actions' => $actions, 'align' => 'start'])
                        </div>
                    </div>
                </div>
            </details>
        @endif
    </div>
</div>
