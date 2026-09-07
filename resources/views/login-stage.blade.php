{{--
    Injected at `SIMPLE_LAYOUT_START`, so it lands inside `.fi-simple-layout`
    and immediately before the container holding the form.

    The element is always emitted, because the data attribute it carries is
    what selects a composition in the compiled stylesheet. Only the two
    compositions that stage the brand separately get any content inside it;
    for the rest this is an empty, `display: contents` marker.

    Whitespace is kept out of the marker on purpose. It becomes a grid item in
    the compositions that lay the layout out as a grid, and a stray text node
    would be one more child than the grid expects.
--}}
@php
    use JohnRivera7\FilamentMia\Enums\LoginLayout;

    /** @var LoginLayout $layout */
    $hasStage = $layout->hasStage();
    $brandOnStage = $layout->movesBrandToStage();
@endphp

<div class="fi-mia-login" data-mia-login="{{ $layout->value }}">@if ($hasStage)<div
        class="fi-mia-login-stage"
        @if (! $brandOnStage)
            {{--
                The facing page of the editorial composition repeats a brand
                name that is already above the form, at display size and in a
                deliberately low contrast. It is ornament, and it is also the
                one composition whose visual order is the reverse of the
                document's, so it is taken out of the accessibility tree
                rather than read out twice in the wrong sequence.
            --}}
            aria-hidden="true"
        @endif
    >
        <div class="fi-mia-login-stage-inner">
            {{--
                Only when a brand image is actually configured. With none,
                Filament's logo component falls back to rendering the panel
                name as text, which the line below already sets at display
                size — the stage would carry the same words twice.
            --}}
            @if ($brandOnStage && filled(filament()->getBrandLogo()))
                <x-filament-panels::logo class="fi-mia-login-stage-logo" />
            @endif

            <p class="fi-mia-login-stage-name">{{ filament()->getBrandName() }}</p>

            @if (filled($tagline))
                <p class="fi-mia-login-stage-tagline">{{ $tagline }}</p>
            @endif
        </div>
    </div>@endif</div>
