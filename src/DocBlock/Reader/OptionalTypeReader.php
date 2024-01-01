<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reader;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Parser;

/**
 * @template-extends Reader<TypeStatement>
 */
final class OptionalTypeReader extends Reader
{
    public function __construct(
        private readonly Parser $parser,
    ) {}

    /**
     * @return Sequence<TypeStatement>|null
     */
    public function read(string $content): ?Sequence
    {
        try {
            $type = $this->parser->parse($content);
        } catch (\Throwable) {
            return null;
        }

        if ($type instanceof TypeStatement) {
            $offset = $this->parser->lastProcessedTokenOffset;

            return new Sequence($type, $offset);
        }

        return null;
    }
}
