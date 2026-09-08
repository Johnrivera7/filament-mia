@php
    use Illuminate\Support\Facades\Storage;

    /** @var array<string, mixed> $data */

    $links = array_values(array_filter(
        is_array($data['links'] ?? null) ? $data['links'] : [],
        fn ($link): bool => is_array($link) && filled($link['label'] ?? null) && filled($link['url'] ?? null),
    ));

    $logo = $data['logo'] ?? null;
    $logo = is_array($logo) ? (array_values(array_filter($logo))[0] ?? null) : $logo;

    $brand = filled($data['brand'] ?? null) ? $data['brand'] : config('app.name');
    $actions = $data['actions'] ?? [];
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
