<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\Type\CallableTypeNode;

/**
 * Reads a type and accepts it only when it is a callable, so that a plain word
 * (which would parse as a type) is left for the following combinators.
 */
final readonly class CallableTypeCombinator extends TypeCombinator
{
    public const string NAME = 'CallableType';

    #[\Override]
    public function __invoke(Cursor $cursor): TypeReference
    {
        $type = parent::__invoke($cursor);

        if (!$type->type instanceof CallableTypeNode) {
            throw new NoMatchException('Expected a callable type');
        }

        return $type;
    }
}
