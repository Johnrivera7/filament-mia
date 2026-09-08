@php
    /** @var array<string, mixed> $data */

    $links = array_values(array_filter(
        is_array($data['links'] ?? null) ? $data['links'] : [],
        fn ($link): bool => is_array($link) && filled($link['label'] ?? null) && filled($link['url'] ?? null),
    ));

    $brand = filled($data['brand'] ?? null) ? $data['brand'] : config('app.name');
@endphp

<div class="mia-page-footer-top">
    <div>
        <p class="mia-page-brand">{{ $brand }}</p>

        @if (filled($data['description'] ?? null))
            <p class="mia-page-body mia-page-flow mia-page-stack--sm">{{ $data['description'] }}</p>
        @endif
    </div>

    @if ($links !== [])
        <nav aria-label="{{ __('filament-mia::page-builder.page.footer_links') }}" class="mia-page-footer-links">
            @foreach ($links as $link)
                <a href="{{ $link['url'] }}" class="mia-page-nav-link">{{ $link['label'] }}</a>
            @endforeach
        </nav>
    @endif
</div>

@if (filled($data['legal'] ?? null))
    <p class="mia-page-meta mia-page-footer-legal">{{ $data['legal'] }}</p>
@endif
