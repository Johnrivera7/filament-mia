{{--
    The eyebrow / heading / lead trio that opens most sections.

    `heading_quiet` is rendered as a continuation of the same heading rather
    than a second one, so a two-part headline stays a single element in the
    document outline.

    Expected data:

      data    The block's own stored content.
      align   `start` or `centre`.
--}}
@php
    $eyebrow = $data['eyebrow'] ?? null;
    $title = $data['heading'] ?? null;
    $quiet = $data['heading_quiet'] ?? null;
    $lead = $data['lead'] ?? null;

    $centred = ($align ?? 'start') === 'centre';
@endphp

@if (filled($eyebrow) || filled($title) || filled($lead))
    <div @class(['mia-page-measure', 'mia-page-centred' => $centred])>
        @if (filled($eyebrow))
            <p class="mia-page-eyebrow">{{ $eyebrow }}</p>
        @endif

        @if (filled($title))
            <h2 @class(['mia-page-h2', 'mia-page-stack--xs' => filled($eyebrow)])>
                {{ $title }}@if (filled($quiet))<span class="mia-page-quiet"> {{ $quiet }}</span>@endif
            </h2>
        @endif

        @if (filled($lead))
            <p @class([
                'mia-page-lead',
                'mia-page-flow',
                'mia-page-stack--sm' => filled($title) || filled($eyebrow),
            ])>{{ $lead }}</p>
        @endif
    </div>
@endif
