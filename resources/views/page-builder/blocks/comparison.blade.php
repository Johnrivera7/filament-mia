@php
    /** @var array<string, mixed> $data */

    $items = array_values(array_filter(
        is_array($data['items'] ?? null) ? $data['items'] : [],
        fn ($item): bool => is_array($item) && filled($item['criterion'] ?? null),
    ));

    /*
     * Two definition lists side by side rather than one table.
     *
     * A two-column comparison table has to either scroll or shrink its type on
     * a phone; repeating the criterion inside each column costs a few words
     * and lets the whole thing stack cleanly instead.
     */
    $columns = [
        [
            'title' => $data['primary_title'] ?? null,
            'subtitle' => $data['primary_subtitle'] ?? null,
            'key' => 'primary',
            'featured' => true,
        ],
        [
            'title' => $data['secondary_title'] ?? null,
            'subtitle' => $data['secondary_subtitle'] ?? null,
            'key' => 'secondary',
            'featured' => false,
        ],
    ];
@endphp

{{-- The rows are the section. A heading with nothing under it is a band of
     empty space, so the whole thing waits until there is something to compare. --}}
@if ($items !== [])
    @include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'start'])

    <div class="mia-page-grid mia-page-grid--2 mia-page-stack">
        @foreach ($columns as $column)
            @continue (blank($column['title']))

            <div @class(['mia-page-panel', 'mia-page-compare--featured' => $column['featured']])>
                <h3 class="mia-page-h3 mia-page-h3--display">{{ $column['title'] }}</h3>

                @if (filled($column['subtitle']))
                    <p @class(['mia-page-meta', 'mia-page-stack--xs', 'mia-page-accent' => $column['featured']])>{{ $column['subtitle'] }}</p>
                @endif

                <dl class="mia-page-divided mia-page-ruled mia-page-stack--md">
                    @foreach ($items as $item)
                        <div class="mia-page-compare-row">
                            <dt>{{ $item['criterion'] }}</dt>
                            <dd>{{ filled($item[$column['key']] ?? null) ? $item[$column['key']] : '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endforeach
    </div>
@endif
