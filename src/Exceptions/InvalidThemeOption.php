<?php

namespace JohnRivera7\FilamentMia\Exceptions;

use InvalidArgumentException;

class InvalidThemeOption extends InvalidArgumentException
{
    public static function forColor(string $option, string $value): self
    {
        return new self(sprintf(
            'Mía theme: [%s] received an invalid color [%s]. Supported formats are hex (#RGB, #RRGGBB), '
            . 'rgb(r, g, b), bare "r, g, b" triplets, and oklch(l c h).',
            $option,
            $value,
        ));
    }

    /** @param  array<string>  $allowed */
    public static function forEnum(string $option, string $value, array $allowed): self
    {
        return new self(sprintf(
            'Mía theme: [%s] received an unsupported value [%s]. Allowed values are: %s.',
            $option,
            $value,
            implode(', ', $allowed),
        ));
    }

    public static function forFontFamily(string $option, string $value): self
    {
        return new self(sprintf(
            'Mía theme: [%s] received an invalid font family [%s]. Use a plain family name such as '
            . '"Jost" or "Cormorant Garamond", without quotes or CSS fallbacks.',
            $option,
            $value,
        ));
    }
}
