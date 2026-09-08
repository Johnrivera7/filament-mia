@php
    /** @var array<string, mixed> $data */

    $items = array_values(array_filter(
        is_array($data['items'] ?? null) ? $data['items'] : [],
        fn ($item): bool => is_array($item) && filled($item['question'] ?? null) && filled($item['answer'] ?? null),
    ));
@endphp

@if ($items !== [])
    <div class="mia-page-split">
        @include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'start'])

        <div class="mia-page-ruled mia-page-faq-list">
            @foreach ($items as $item)
                {{--
                    Native `<details>`: keyboard-operable, announced as a
                    disclosure, and open by default for the first item so the
                    section does not read as an empty list of links.
                --}}
                <details class="mia-page-faq-item" @if ($loop->first) open @endif>
                    <summary>{{ $item['question'] }}</summary>
                    <p class="mia-page-body mia-page-flow mia-page-faq-answer">{{ $item['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
@endif
