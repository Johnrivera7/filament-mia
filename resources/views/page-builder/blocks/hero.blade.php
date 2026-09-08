@php
    use Illuminate\Support\Facades\Storage;

    /** @var array<string, mixed> $data */

    $image = $data['image'] ?? null;
    $image = is_array($image) ? (array_values(array_filter($image))[0] ?? null) : $image;

    $rows = array_values(array_filter(
        is_array($data['card_rows'] ?? null) ? $data['card_rows'] : [],
        fn ($row): bool => is_array($row) && (filled($row['title'] ?? null) || filled($row['label'] ?? null)),
    ));

    $showsCard = ($data['card_visible'] ?? true)
        && (filled($data['card_title'] ?? null) || $rows !== []);

    $hasAside = $showsCard || filled($image);
    $eyebrow = $data['eyebrow'] ?? null;
@endphp

{{--
    Purely decorative wash. Kept behind the content by taking it out of flow —
    the content itself stays in normal flow, which is what stops a long
    headline from overlapping anything on a narrow screen.
--}}
<div class="mia-page-glow" aria-hidden="true"></div>

<div @class(['mia-page-hero', 'mia-page-hero--aside' => $hasAside])>
    <div>
        @if (filled($eyebrow))
            <p class="mia-page-eyebrow mia-page-rise">{{ $eyebrow }}</p>
        @endif

        <h1 @class(['mia-page-display', 'mia-page-rise', 'mia-page-rise--1', 'mia-page-stack--sm' => filled($eyebrow)])>
            {{ $data['heading'] ?? '' }}@if (filled($data['heading_accent'] ?? null))<span class="mia-page-accent"> {{ $data['heading_accent'] }}</span>@endif
        </h1>

        @if (filled($data['lead'] ?? null))
            <p class="mia-page-lead mia-page-flow mia-page-rise mia-page-rise--2 mia-page-stack--md mia-page-measure">{{ $data['lead'] }}</p>
        @endif

        <div class="mia-page-rise mia-page-rise--3 mia-page-stack--md">
            @include('filament-mia::page-builder.parts.actions', [
                'actions' => $data['actions'] ?? [],
                'align' => 'start',
            ])
        </div>
    </div>

    @if ($hasAside)
        <div class="mia-page-rise mia-page-rise--2">
            @if (filled($image))
                <img
                    src="{{ Storage::disk('public')->url($image) }}"
                    alt="{{ $data['image_alt'] ?? '' }}"
                    @if (blank($data['image_alt'] ?? null)) aria-hidden="true" @endif
                    class="mia-page-hero-image"
                    loading="eager"
                    decoding="async"
                >
            @endif

            @if ($showsCard)
                {{--
                    A sample of what the product shows, not a screenshot: it is
                    markup, so it costs nothing to load, stays legible at any
                    width and follows the configured palette.
                --}}
                <div class="mia-page-card" role="presentation">
                    <div class="mia-page-sample-head">
                        @if (filled($data['card_eyebrow'] ?? null))
                            <span class="mia-page-dot">
                                <span class="mia-page-eyebrow">{{ $data['card_eyebrow'] }}</span>
                            </span>
                        @endif

                        @if (filled($data['card_note'] ?? null))
                            <span class="mia-page-meta">{{ $data['card_note'] }}</span>
                        @endif
                    </div>

                    @if (filled($data['card_title'] ?? null))
                        <p class="mia-page-h3 mia-page-h3--display mia-page-stack--sm">{{ $data['card_title'] }}</p>
                    @endif

                    @if (filled($data['card_subtitle'] ?? null))
                        <p class="mia-page-meta mia-page-stack--xs">{{ $data['card_subtitle'] }}</p>
                    @endif

                    @if ($rows !== [])
                        <div class="mia-page-divided mia-page-ruled mia-page-stack--md">
                            @foreach ($rows as $row)
                                <div class="mia-page-sample-row">
                                    @if (filled($row['label'] ?? null))
                                        <p class="mia-page-eyebrow">{{ $row['label'] }}</p>
                                    @endif

                                    @if (filled($row['title'] ?? null))
                                        <p class="mia-page-h3 mia-page-stack--xs">{{ $row['title'] }}</p>
                                    @endif

                                    @if (filled($row['note'] ?? null))
                                        <p class="mia-page-meta">{{ $row['note'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
