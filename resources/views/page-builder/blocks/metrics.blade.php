@php
    /** @var array<string, mixed> $data */

    $items = array_values(array_filter(
        is_array($data['items'] ?? null) ? $data['items'] : [],
        fn ($item): bool => is_array($item) && filled($item['value'] ?? null) && filled($item['label'] ?? null),
    ));

    /*
     * Spelled out rather than composed at runtime. A class assembled from
     * stored content could name a rule that does not exist, and would fail
     * silently rather than loudly.
     */
    $columns = match (count($items)) {
        1, 2 => 'mia-page-grid--2',
        3 => 'mia-page-grid--3',
        default => 'mia-page-grid--4',
    };

    $eyebrow = $data['eyebrow'] ?? null;
    $heading = $data['heading'] ?? null;
    $hasHeading = filled($eyebrow) || filled($heading);
@endphp

@if ($items !== [])
    @if ($hasHeading)
        <div class="mia-page-measure">
            @if (filled($eyebrow))
                <p class="mia-page-eyebrow">{{ $eyebrow }}</p>
            @endif

            @if (filled($heading))
                <p class="mia-page-h3 mia-page-h3--display mia-page-stack--xs">{{ $heading }}</p>
            @endif
        </div>
    @endif

    <dl @class(['mia-page-grid', $columns, 'mia-page-stack--md' => $hasHeading])>
        @foreach ($items as $item)
            <div class="mia-page-metric">
                <dt class="mia-page-metric-value">{{ $item['value'] }}</dt>
                <dd class="mia-page-body mia-page-stack--xs">{{ $item['label'] }}</dd>
            </div>
        @endforeach
    </dl>
@endif
