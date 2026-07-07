<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a version number, that is a word beginning with a digit. Anything that
 * does not begin with a digit is left for the following description.
 *
 * @template-implements CombinatorInterface<non-empty-string>
 */
final readonly class VersionCombinator implements CombinatorInterface
{
    public const string NAME = 'Version';

    private const string DIGITS = '0123456789';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        // A version begins with a digit; \strspn avoids relying on the ctype
        // extension, which may not be installed.
        if (\strspn($cursor->peek(1), self::DIGITS) === 0) {
            throw new NoMatchException('Expected a version');
        }

        $version = $cursor->readWord();

        if ($version === '') {
            throw new NoMatchException('Expected a version');
        }

        return $version;
    }
}
