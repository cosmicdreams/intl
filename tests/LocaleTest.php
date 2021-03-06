<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\Exception\UnknownLocaleException;
use CommerceGuys\Intl\Locale;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Locale
 */
class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::resolve
     */
    public function testResolve()
    {
        $availableLocales = ['bs-Cyrl', 'bs', 'en'];
        $locale = Locale::resolve($availableLocales, 'bs-Cyrl-BA');
        $this->assertEquals('bs-Cyrl', $locale);

        $locale = Locale::resolve($availableLocales, 'bs-Latn-BA');
        $this->assertEquals('bs', $locale);

        $locale = Locale::resolve($availableLocales, 'de', 'en');
        $this->assertEquals('en', $locale);
    }

    /**
     * @covers ::resolve
     */
    public function testResolveWithoutResult()
    {
        $this->setExpectedException(UnknownLocaleException::class);
        $availableLocales = ['bs', 'en'];
        $locale = Locale::resolve($availableLocales, 'de');
    }

    /**
     * @covers ::canonicalize
     */
    public function testCanonicalize()
    {
        $locale = Locale::canonicalize('BS_cyrl-ba');
        $this->assertEquals('bs-Cyrl-BA', $locale);

        $locale = Locale::canonicalize(null);
        $this->assertEquals(null, $locale);
    }

    /**
     * @covers ::getCandidates
     */
    public function testCandidates()
    {
        $candidates = Locale::getCandidates('en-US');
        $this->assertEquals(['en-US', 'en'], $candidates);

        $candidates = Locale::getCandidates('en-US', 'en');
        $this->assertEquals(['en-US', 'en'], $candidates);

        $candidates = Locale::getCandidates('sr', 'en-US');
        $this->assertEquals(['sr', 'en-US', 'en'], $candidates);

        $candidates = Locale::getCandidates('en-AU');
        $this->assertEquals(['en-AU', 'en-001', 'en'], $candidates);

        $candidates = Locale::getCandidates('sh');
        $this->assertEquals(['sr-Latn', 'root'], $candidates);
    }

    /**
     * @covers ::getParent
     */
    public function testParent()
    {
        $this->assertEquals('sr-Latn', Locale::getParent('sr-Latn-RS'));
        $this->assertEquals('root', Locale::getParent('sr-Latn'));
        $this->assertEquals(null, Locale::getParent('root'));
        $this->assertEquals(null, Locale::getParent('sr'));
    }

    /**
     * @covers ::replaceAlias
     */
    public function testReplaceAlias()
    {
        $locale = Locale::replaceAlias('zh-CN');
        $this->assertEquals('zh-Hans-CN', $locale);

        $locale = Locale::replaceAlias(null);
        $this->assertEquals(null, $locale);
    }
}
