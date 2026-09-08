@php
    /** @var array<string, mixed> $data */
@endphp

<div class="mia-page-measure mia-page-centred">
    {{--
        The theme's botanical mark, echoing the one the panel uses in its empty
        states. Hidden from assistive technology because it says nothing the
        words below do not.
    --}}
    <div class="mia-page-mark" aria-hidden="true"></div>

    @include('filament-mia::page-builder.parts.heading', ['data' => $data, 'align' => 'centre'])

    <div class="mia-page-stack--md">
        @include('filament-mia::page-builder.parts.actions', [
            'actions' => $data['actions'] ?? [],
            'align' => 'centre',
        ])
    </div>
</div>
