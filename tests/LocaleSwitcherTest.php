<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Http\Request;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\MiaTheme;
use JohnRivera7\FilamentMia\Support\LocaleLibrary;
use JohnRivera7\FilamentMia\Support\LocaleStore;

class LocaleSwitcherTest extends TestCase
{
    public function test_the_switcher_is_off_until_it_is_asked_for(): void
    {
        // The theme must not take over a locale nobody handed it.
        $this->assertSame([], MiaTheme::make()->getLocales());
    }

    public function test_the_default_pair_is_the_two_languages_the_theme_ships(): void
    {
        $this->assertSame(
            ['en' => 'English', 'es' => 'Español'],
            MiaTheme::make()->localeSwitcher()->getLocales(),
        );
    }

    public function test_a_plain_list_is_labelled_with_each_language_own_name(): void
    {
        $this->assertSame(
            ['en' => 'English', 'pt_BR' => 'Português (Brasil)', 'ja' => '日本語'],
            MiaTheme::make()->localeSwitcher(['en', 'pt_BR', 'ja'])->getLocales(),
        );
    }

    public function test_a_given_label_wins_over_the_built_in_name(): void
    {
        $this->assertSame(
            ['en' => 'English (US)', 'es' => 'Español'],
            MiaTheme::make()->localeSwitcher(['en' => 'English (US)', 'es'])->getLocales(),
        );
    }

    public function test_the_switcher_can_be_turned_off_again(): void
    {
        $theme = MiaTheme::make()
            ->localeSwitcher(['en', 'es'])
            ->localeSwitcher(false);

        $this->assertSame([], $theme->getLocales());
    }

    public function test_a_single_locale_is_rejected(): void
    {
        // A switcher with one option is a configuration mistake, not a switcher.
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->localeSwitcher(['en']);
    }

    public function test_a_malformed_locale_code_is_rejected(): void
    {
        $this->expectException(InvalidThemeOption::class);

        MiaTheme::make()->localeSwitcher(['en', 'Español']);
    }

    public function test_only_the_offered_languages_are_recognised(): void
    {
        $theme = MiaTheme::make()->localeSwitcher(['en', 'es']);

        $this->assertTrue($theme->hasLocale('es'));

        // The cookie is shared by every panel in the application, so a value
        // another panel offers must not be applied here.
        $this->assertFalse($theme->hasLocale('fr'));
    }

    public function test_an_unknown_locale_falls_back_to_its_code(): void
    {
        // Better a switcher labelled with a code than a package that refuses
        // to boot over a language it has no name for.
        $this->assertSame('zz', LocaleLibrary::label('zz'));
    }

    public function test_the_stored_choice_is_read_from_the_cookie(): void
    {
        $request = Request::create('/admin');
        $request->cookies->set(LocaleStore::NAME, 'es');

        $this->assertSame('es', LocaleStore::read($request));
    }

    public function test_no_cookie_means_no_choice(): void
    {
        $this->assertNull(LocaleStore::read(Request::create('/admin')));
    }
}
