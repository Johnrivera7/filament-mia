@php
    use Illuminate\Support\Facades\Storage;

    /** @var array<string, mixed> $data */

    $items = array_values(array_map(
        function (array $item): array {
            $avatar = $item['avatar'] ?? null;
            $item['avatar'] = is_array($avatar) ? (array_values(array_filter($avatar))[0] ?? null) : $avatar;

            return $item;
        },
        array_filter(
            is_array($data['items'] ?? null) ? $data['items'] : [],
            fn ($item): bool => is_array($item) && filled($item['quote'] ?? null),
        ),
    ));

    $columns = match (count($items)) {
        1 => null,
        2 => 'mia-page-grid--2',
        default => 'mia-page-grid--3',
    };
@endphp

@if ($items !== [])
    @include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'start'])

    <div @class(['mia-page-grid', $columns, 'mia-page-measure' => $columns === null, 'mia-page-stack'])>
        @foreach ($items as $item)
            <figure class="mia-page-panel mia-page-figure">
                <blockquote class="mia-page-quote">{{ $item['quote'] }}</blockquote>

                <figcaption class="mia-page-byline">
                    @if (filled($item['avatar']))
                        <img
                            src="{{ Storage::disk('public')->url($item['avatar']) }}"
                            alt=""
                            loading="lazy"
                            decoding="async"
                        >
                    @endif

                    <span>
                        <span class="mia-page-byline-name">{{ $item['author'] ?? '' }}</span>

                        @if (filled($item['role'] ?? null))
                            <span class="mia-page-meta">{{ $item['role'] }}</span>
                        @endif
                    </span>
                </figcaption>
            </figure>
        @endforeach
    </div>
@endif
