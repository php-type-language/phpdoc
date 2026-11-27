<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads an author name, that is everything up to an optional "<email>".
 *
 * @template-implements CombinatorInterface<non-empty-string>
 */
final class AuthorNameCombinator implements CombinatorInterface
{
    public const NAME = 'AuthorName';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        $name = \rtrim($cursor->readUntil('<'));

        if ($name === '') {
            $cursor->fail('Expected an author name');
        }

        return $name;
    }
}
