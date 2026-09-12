<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\Parser\Partial\SuccessfulParsedResult;
use TypeLang\Parser\TypeParserInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a type from the cursor, consuming exactly the part that forms
 * a valid type and leaving any trailing text (such as a description) untouched.
 *
 * The result pairs the parsed type with the source text it was read from.
 *
 * @template-implements CombinatorInterface<TypeReference>
 */
class TypeCombinator implements CombinatorInterface
{
    public const NAME = 'Type';

    public function __construct(
        private readonly TypeParserInterface $typeParser
    ) {}

    public function __invoke(Cursor $cursor): TypeReference
    {
        $start = $cursor->getPosition();
        $source = $cursor->readRemainder();

        if ($source === '') {
            throw new NoMatchException('Expected a type');
        }

        $result = $this->typeParser->partial($source);

        if (!$result instanceof SuccessfulParsedResult) {
            throw new NoMatchException($result->message);
        }

        $cursor->moveTo($start + $result->offset);

        // The tolerant offset also covers the whitespace up to the next token,
        // so the trailing run is trimmed off the preserved type text.
        $consumed = \rtrim(\substr($source, 0, $result->offset));

        if ($consumed === '') {
            throw new NoMatchException('Expected a type');
        }

        return new TypeReference($result->type, $consumed);
    }
}
