<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Parser;
use TypeLang\PhpDoc\Parser\Exception\InvalidTypeException;

/**
 * @template-extends Reader<TypeStatement>
 */
final class TypeReader extends Reader
{
    public function __construct(
        private readonly Parser $parser,
    ) {}

    /**
     * @return Sequence<TypeStatement>
     * @throws InvalidTypeException
     */
    public function read(string $content): Sequence
    {
        try {
            $type = $this->parser->parse($content);
        } catch (\Throwable $e) {
            throw InvalidTypeException::fromInvalidType($e);
        }

        if ($type instanceof TypeStatement) {
            $offset = $this->parser->lastProcessedTokenOffset;

            return new Sequence($type, $offset);
        }

        throw InvalidTypeException::fromInvalidType();
    }
}
