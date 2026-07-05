<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a variable ("$name") and returns its name without the leading "$".
 *
 * @implements CombinatorInterface<non-empty-string>
 */
final readonly class VariableCombinator implements CombinatorInterface
{
    public const string NAME = 'Variable';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        if (!$cursor->readLiteral('$')) {
            throw new NoMatchException('Expected a variable');
        }

        $name = $cursor->readPhpIdentifier();

        // A variable is a single word: nothing but whitespace may follow it.
        if ($name === '' || $cursor->readWord() !== '') {
            throw new NoMatchException('Expected a variable');
        }

        return $name;
    }
}
