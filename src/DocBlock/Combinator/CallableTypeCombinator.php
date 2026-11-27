<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\Type\Callable\CallableTypeNode;

/**
 * Reads a type and accepts it only when it is a callable. Any other type,
 * a plain word included, is left for the following combinators.
 */
final class CallableTypeCombinator extends TypeCombinator
{
    public const NAME = 'CallableType';

    #[\Override]
    public function __invoke(Cursor $cursor): TypeReference
    {
        $type = parent::__invoke($cursor);

        if (!$type->type instanceof CallableTypeNode) {
            $cursor->fail('Expected a callable type');
        }

        return $type;
    }
}
