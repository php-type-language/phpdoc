<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\LegacyUrlValidator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\UrlValidatorInterface;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\WhatWgUrlValidator;
use TypeLang\PhpDoc\DocBlock\Reference\UrlReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads a URL with a host as defined by WHATWG: `https://example.com/path`.
 *
 * A word without a scheme or a host is not a URL and is left for the
 * following combinators.
 *
 * @template-implements CombinatorInterface<UrlReference>
 */
final class UrlCombinator implements CombinatorInterface
{
    public const NAME = 'URL';

    private readonly UrlValidatorInterface $validator;

    public function __construct(?UrlValidatorInterface $validator = null)
    {
        $this->validator = $validator ?? self::createValidator();
    }

    public function __invoke(Cursor $cursor): UrlReference
    {
        $url = $cursor->readWord();

        if ($url === '' || !$this->validator->isValid($url)) {
            $cursor->fail('Expected a URL');
        }

        return new UrlReference($url);
    }

    private static function createValidator(): UrlValidatorInterface
    {
        if (\PHP_VERSION_ID >= 80500) {
            return new WhatWgUrlValidator();
        }

        return new LegacyUrlValidator();
    }
}
