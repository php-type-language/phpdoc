<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Tag\Visibility;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads an access level, that is one of the "public", "protected" or
 * "private" keywords.
 *
 * @template-implements CombinatorInterface<Visibility>
 */
final readonly class VisibilityCombinator implements CombinatorInterface
{
    public const string NAME = 'Visibility';

    public function __invoke(Cursor $cursor): Visibility
    {
        $access = Visibility::tryFrom($cursor->readWord());

        if ($access === null) {
            throw new NoMatchException('Expected an access (one of "public", "protected" or "private") level');
        }

        return $access;
    }
}
