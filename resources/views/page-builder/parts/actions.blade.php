{{--
    A row of call-to-action links.

    Buttons stack full-width below `sm` on purpose: a 44px tap target that
    spans the column is the single biggest difference between a hero that works
    on a phone and one that does not.

    Expected data:

      actions   Ordered list of ['label' => …, 'url' => …, 'style' => …].
      align     `start` or `centre`.
--}}
@php
    $styles = [
        'solid' => 'mia-page-btn--solid',
        'outline' => 'mia-page-btn--outline',
        'text' => 'mia-page-btn--text',
    ];

    $items = array_values(array_filter(
        is_array($actions ?? null) ? $actions : [],
        fn ($action): bool => is_array($action)
            && filled($action['label'] ?? null)
            && filled($action['url'] ?? null),
    ));

    $centred = ($align ?? 'start') === 'centre';
@endphp

@if ($items !== [])
    <div @class(['mia-page-actions', 'mia-page-actions--centred' => $centred])>
        @foreach ($items as $action)
            <a
                href="{{ $action['url'] }}"
                @class(['mia-page-btn', $styles[$action['style'] ?? 'solid'] ?? $styles['solid']])
                @if (str_starts_with($action['url'], 'http') && ! str_starts_with($action['url'], url('/')))
                    rel="noopener"
                @endif
            >{{ $action['label'] }}</a>
        @endforeach
    </div>
@endif
