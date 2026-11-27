<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\LegacyUriValidator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\Rfc3986UriValidator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\UriValidatorInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads a URI, that is a well-formed word as defined by RFC 3986.
 *
 * @template-implements CombinatorInterface<UriReference>
 */
final class UriCombinator implements CombinatorInterface
{
    public const NAME = 'URI';

    private readonly UriValidatorInterface $validator;

    public function __construct(?UriValidatorInterface $validator = null)
    {
        $this->validator = $validator ?? self::createValidator();
    }

    public function __invoke(Cursor $cursor): UriReference
    {
        $uri = $cursor->readWord();

        // A word of the delimiters only refers to nothing
        if ($uri === '' || \trim($uri, '/?#') === '' || !$this->validator->isValid($uri)) {
            $cursor->fail('Expected a URI');
        }

        return new UriReference($uri);
    }

    private static function createValidator(): UriValidatorInterface
    {
        if (\PHP_VERSION_ID >= 80500) {
            return new Rfc3986UriValidator();
        }

        return new LegacyUriValidator();
    }
}
