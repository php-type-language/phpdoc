<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads a variable ("$name") and returns its name without the leading "$".
 *
 * @template-implements CombinatorInterface<non-empty-string>
 */
final class VariableCombinator implements CombinatorInterface
{
    public const NAME = 'Variable';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        if (!$cursor->readLiteral('$')) {
            $cursor->fail('Expected a variable');
        }

        $name = $cursor->readPhpIdentifier();

        // A variable is a single word: nothing but whitespace may follow it.
        if ($name === '' || $cursor->readWord() !== '') {
            $cursor->fail('Expected a variable');
        }

        return $name;
    }
}
