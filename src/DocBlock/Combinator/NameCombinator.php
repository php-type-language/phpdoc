<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a template parameter name, that is a single identifier.
 *
 * @implements CombinatorInterface<non-empty-string>
 */
final readonly class NameCombinator implements CombinatorInterface
{
    public const string NAME = 'Name';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        $name = $cursor->readPhpIdentifier();

        if ($name === '') {
            throw new NoMatchException('Expected a name');
        }

        return $name;
    }
}
