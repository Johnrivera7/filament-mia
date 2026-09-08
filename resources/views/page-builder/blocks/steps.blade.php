@php
    /** @var array<string, mixed> $data */

    $items = array_values(array_filter(
        is_array($data['items'] ?? null) ? $data['items'] : [],
        fn ($item): bool => is_array($item) && filled($item['title'] ?? null),
    ));
@endphp

@include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'start'])

@if ($items !== [])
    {{--
        An ordered list, because the order is the meaning. The visible
        numbering is decorative and hidden from assistive technology, which
        already announces the list positions.
    --}}
    <ol class="mia-page-divided mia-page-ruled mia-page-stack">
        @foreach ($items as $index => $item)
            <li class="mia-page-step">
                <span class="mia-page-step-number" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>

                <h3 class="mia-page-h3 mia-page-h3--display">{{ $item['title'] }}</h3>

                @if (filled($item['text'] ?? null))
                    <p class="mia-page-body mia-page-measure">{{ $item['text'] }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@endif
