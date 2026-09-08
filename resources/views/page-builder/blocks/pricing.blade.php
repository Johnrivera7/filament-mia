@php
    /** @var array<string, mixed> $data */

    $plans = array_values(array_map(
        function (array $plan): array {
            // Whoever edits the page types one benefit per line, which is far
            // quicker than a nested repeater and reads the same on the page.
            $plan['points'] = array_values(array_filter(array_map(
                'trim',
                preg_split('/\R/', (string) ($plan['features'] ?? '')) ?: [],
            )));

            return $plan;
        },
        array_filter(
            is_array($data['items'] ?? null) ? $data['items'] : [],
            fn ($plan): bool => is_array($plan) && filled($plan['name'] ?? null),
        ),
    ));

    $columns = match (count($plans)) {
        1 => null,
        2 => 'mia-page-grid--2',
        3 => 'mia-page-grid--3',
        default => 'mia-page-grid--4',
    };
@endphp

@if ($plans !== [])
    @include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'centre'])

    <div @class(['mia-page-grid', $columns, 'mia-page-measure' => $columns === null, 'mia-page-stack'])>
        @foreach ($plans as $plan)
            <div @class(['mia-page-card', 'mia-page-plan', 'mia-page-plan--featured' => (bool) ($plan['featured'] ?? false)])>
                <h3 class="mia-page-h3">{{ $plan['name'] }}</h3>

                @if (filled($plan['price'] ?? null))
                    <p class="mia-page-plan-price">
                        <span class="mia-page-plan-amount">{{ $plan['price'] }}</span>

                        @if (filled($plan['period'] ?? null))
                            <span class="mia-page-meta">{{ $plan['period'] }}</span>
                        @endif
                    </p>
                @endif

                @if (filled($plan['description'] ?? null))
                    <p class="mia-page-body mia-page-stack--sm">{{ $plan['description'] }}</p>
                @endif

                @if ($plan['points'] !== [])
                    <ul class="mia-page-plan-list">
                        @foreach ($plan['points'] as $point)
                            <li class="mia-page-tick">{{ $point }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (filled($plan['action_label'] ?? null) && filled($plan['action_url'] ?? null))
                    <div class="mia-page-plan-action">
                        <a
                            href="{{ $plan['action_url'] }}"
                            @class([
                                'mia-page-btn',
                                'mia-page-btn--block',
                                'mia-page-btn--solid' => (bool) ($plan['featured'] ?? false),
                                'mia-page-btn--outline' => ! ($plan['featured'] ?? false),
                            ])
                        >{{ $plan['action_label'] }}</a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if (filled($data['note'] ?? null))
        <p class="mia-page-meta mia-page-centred mia-page-stack--md">{{ $data['note'] }}</p>
    @endif
@endif
