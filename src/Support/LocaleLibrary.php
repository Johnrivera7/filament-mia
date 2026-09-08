<?php

namespace JohnRivera7\FilamentMia\Support;

use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use Locale;

/**
 * Locale codes and the names shown for them.
 *
 * Languages are labelled with their own name — "Español", not "Spanish" —
 * because the switcher's whole purpose is to be readable by someone who cannot
 * read the language currently on screen. That also means these labels are
 * deliberately *not* translated.
 */
class LocaleLibrary
{
    /**
     * Names for the locales Filament itself ships translations for, plus the
     * regional variants it distinguishes.
     *
     * @var array<string, string>
     */
    protected const NAMES = [
        'am' => 'አማርኛ',
        'ar' => 'العربية',
        'az' => 'Azərbaycanca',
        'bg' => 'Български',
        'bn' => 'বাংলা',
        'bs' => 'Bosanski',
        'ca' => 'Català',
        'ckb' => 'کوردی',
        'cs' => 'Čeština',
        'cy' => 'Cymraeg',
        'da' => 'Dansk',
        'de' => 'Deutsch',
        'el' => 'Ελληνικά',
        'en' => 'English',
        'es' => 'Español',
        'et' => 'Eesti',
        'eu' => 'Euskara',
        'fa' => 'فارسی',
        'fi' => 'Suomi',
        'fil' => 'Filipino',
        'fr' => 'Français',
        'he' => 'עברית',
        'hi' => 'हिन्दी',
        'hr' => 'Hrvatski',
        'hu' => 'Magyar',
        'hy' => 'Հայերեն',
        'id' => 'Bahasa Indonesia',
        'it' => 'Italiano',
        'ja' => '日本語',
        'ka' => 'ქართული',
        'km' => 'ភាសាខ្មែរ',
        'ko' => '한국어',
        'ku' => 'Kurdî',
        'lt' => 'Lietuvių',
        'lv' => 'Latviešu',
        'mn' => 'Монгол',
        'ms' => 'Bahasa Melayu',
        'my' => 'ဗမာစာ',
        'nb' => 'Norsk bokmål',
        'ne' => 'नेपाली',
        'nl' => 'Nederlands',
        'pl' => 'Polski',
        'pt' => 'Português',
        'pt_BR' => 'Português (Brasil)',
        'pt_PT' => 'Português (Portugal)',
        'ro' => 'Română',
        'ru' => 'Русский',
        'sk' => 'Slovenčina',
        'sl' => 'Slovenščina',
        'sq' => 'Shqip',
        'sr' => 'Српски',
        'sv' => 'Svenska',
        'sw' => 'Kiswahili',
        'ta' => 'தமிழ்',
        'th' => 'ไทย',
        'tr' => 'Türkçe',
        'uk' => 'Українська',
        'uz' => 'Oʻzbekcha',
        'vi' => 'Tiếng Việt',
        'zh_CN' => '简体中文',
        'zh_TW' => '繁體中文',
    ];

    /**
     * Turn whatever the panel was given into `code => label` pairs.
     *
     * Accepts a plain list of codes, a map of codes to labels, or a mix of the
     * two, so an application can name a language itself where the built-in
     * name is not the one it wants:
     *
     *     ['en', 'es']
     *     ['en' => 'English (US)', 'es']
     *
     * @param  array<int|string, string>  $locales
     * @return array<string, string>
     */
    public static function normalise(array $locales): array
    {
        $normalised = [];

        foreach ($locales as $key => $value) {
            $code = is_int($key) ? $value : $key;
            $label = is_int($key) ? static::label($value) : $value;

            static::assertValidCode($code);

            $normalised[$code] = $label;
        }

        return $normalised;
    }

    /**
     * The name to show for a locale.
     *
     * The curated list first, so capitalisation and script are predictable,
     * then `intl` if the extension is available, then the code itself. A
     * switcher that says "eo" is worse than no switcher, but not by much, and
     * it is better than failing to boot.
     */
    public static function label(string $code): string
    {
        if (isset(static::NAMES[$code])) {
            return static::NAMES[$code];
        }

        $normalised = str_replace('-', '_', $code);

        if (isset(static::NAMES[$normalised])) {
            return static::NAMES[$normalised];
        }

        if (class_exists(Locale::class)) {
            $name = Locale::getDisplayLanguage($normalised, $normalised);

            if (filled($name) && ($name !== $normalised)) {
                return mb_convert_case($name, MB_CASE_TITLE);
            }
        }

        return $code;
    }

    protected static function assertValidCode(string $code): void
    {
        if (preg_match('/^[a-z]{2,3}([_-][a-zA-Z0-9]{2,8})*$/i', $code) !== 1) {
            throw new InvalidThemeOption(sprintf(
                'Mía theme: [localeSwitcher] received an invalid locale code [%s]. Use codes that match '
                . 'the directories in your lang folder, such as "en", "es" or "pt_BR".',
                $code,
            ));
        }
    }
}
