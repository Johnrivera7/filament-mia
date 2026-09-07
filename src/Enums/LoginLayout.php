<?php

namespace JohnRivera7\FilamentMia\Enums;

use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;

/**
 * Composition of the screens shown before sign-in.
 *
 * The sign-in screen is the only part of a panel a visitor sees without an
 * account, so it carries more weight than its size suggests. These are five
 * different stagings of it, not one layout with five paddings: what changes is
 * where the form sits and what occupies the rest of the viewport.
 *
 * The palette, the type pairing and the shape treatment are identical across
 * all of them. Only the composition differs.
 *
 * Every case applies to the whole authentication flow — sign-in, registration,
 * password reset and multi-factor — so a panel does not change shape halfway
 * through logging in.
 */
enum LoginLayout: string
{
    /**
     * A single card centred on the canvas, over two very soft pools of warm
     * light. The quietest of the five, and the default.
     */
    case Card = 'card';

    /**
     * Two columns. One is brand territory — a deep, warm field carrying the
     * logo, the panel name and an optional line of copy — and the other holds
     * the form on the cream canvas. Below the tablet breakpoint the brand
     * column becomes a short banner above the form.
     */
    case Split = 'split';

    /**
     * A warm gradient field running to every edge, with the form floating on
     * frosted glass above it. The scrim behind the glass is deliberately heavy
     * enough to hold text contrast.
     */
    case Bleed = 'bleed';

    /**
     * Asymmetric and print-like. The form is anchored to one side with no card
     * around it, sitting directly on the canvas behind a hairline, and the
     * facing side is left as air with a large typographic brand mark.
     */
    case Editorial = 'editorial';

    /**
     * A narrow, tall column, centred, with a brand medallion above the
     * heading and no card edge at all. The canvas grades vertically.
     */
    case Portal = 'portal';

    /**
     * Whether the composition needs the injected brand stage.
     *
     * The other cases compose entirely out of the markup Filament already
     * renders, so nothing is injected into them.
     */
    public function hasStage(): bool
    {
        return match ($this) {
            self::Split, self::Editorial => true,
            self::Card, self::Bleed, self::Portal => false,
        };
    }

    /**
     * Whether the composition shows the panel's brand mark on the stage
     * rather than above the form.
     */
    public function movesBrandToStage(): bool
    {
        return $this === self::Split;
    }

    public static function fromValue(self|string $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::tryFrom(strtolower(trim($value)))
            ?? throw InvalidThemeOption::forEnum('loginLayout', $value, self::values());
    }

    /** @return array<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
