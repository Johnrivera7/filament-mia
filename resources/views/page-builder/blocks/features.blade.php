@php
    use JohnRivera7\FilamentMia\PageBuilder\BlockCatalog;

    /** @var array<string, mixed> $data */

    $items = array_values(array_filter(
        is_array($data['items'] ?? null) ? $data['items'] : [],
        fn ($item): bool => is_array($item) && filled($item['title'] ?? null),
    ));

    $columns = (int) ($data['columns'] ?? 3) === 2 ? 'mia-page-grid--2' : 'mia-page-grid--3';
@endphp

@include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'start'])

@if ($items !== [])
    <ul @class(['mia-page-grid', $columns, 'mia-page-stack'])>
        @foreach ($items as $item)
            <li>
                @if (BlockCatalog::isKnownIcon($item['icon'] ?? null))
                    <span class="mia-page-feature-icon" aria-hidden="true">@svg($item['icon'])</span>
                @endif

                <h3 class="mia-page-h3">{{ $item['title'] }}</h3>

                @if (filled($item['text'] ?? null))
                    <p class="mia-page-body mia-page-stack--xs">{{ $item['text'] }}</p>
                @endif
            </li>
        @endforeach
    </ul>
@endif
