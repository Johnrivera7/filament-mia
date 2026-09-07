{{--
    A live preview of the selected sign-in composition.

    This is not a drawing of one. It is the real markup Filament renders for
    the simple layout, with the real marker element, so every rule in the
    compiled stylesheet applies to it exactly as it does to the sign-in screen
    itself — the gradients, the glass, the stage, the hairlines, the type.
    Scaled down and given a fixed height by the stylesheet.

    The marker's data attribute is bound with Alpine rather than rendered from
    PHP, so switching composition redraws the preview without waiting for a
    round trip.
--}}
<div class="fi-mia-login-preview">
    <div class="fi-mia-login-preview-frame" aria-hidden="true">
        <div class="fi-simple-layout">
            <div
                class="fi-mia-login"
                :data-mia-login="$wire.data.login_layout ?? 'card'"
            >
                <div class="fi-mia-login-stage">
                    <div class="fi-mia-login-stage-inner">
                        <p class="fi-mia-login-stage-name">
                            {{ filament()->getBrandName() }}
                        </p>

                        <p
                            class="fi-mia-login-stage-tagline"
                            x-text="
                                $wire.data.login_tagline ||
                                    @js(__('filament-mia::customizer.login.tagline_placeholder'))
                            "
                        ></p>
                    </div>
                </div>
            </div>

            <div class="fi-simple-main-ctn">
                <main class="fi-simple-main fi-width-lg">
                    <div class="fi-simple-page">
                        <div class="fi-simple-page-content">
                            <div class="fi-simple-header">
                                <x-filament-panels::logo />

                                <h1 class="fi-simple-header-heading">
                                    {{ __('filament-mia::customizer.login.sample_heading') }}
                                </h1>
                            </div>

                            <div class="fi-fo-component-ctn">
                                @foreach (['email', 'password'] as $field)
                                    <div class="fi-fo-field-wrp">
                                        <div class="fi-fo-field-wrp-label-ctn">
                                            <label class="fi-fo-field-wrp-label">
                                                {{ __("filament-mia::customizer.login.sample_{$field}") }}
                                            </label>
                                        </div>

                                        <div class="fi-fo-field-wrp-content-ctn">
                                            <div class="fi-input-wrp">
                                                <div class="fi-input-wrp-input">
                                                    <input class="fi-input" type="text" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="fi-form-actions">
                                    <div class="fi-ac">
                                        {{-- Filament's own button, so the preview shows the real one. --}}
                                        <x-filament::button tag="div">
                                            {{ __('filament-mia::customizer.login.sample_action') }}
                                        </x-filament::button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
