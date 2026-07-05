<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Bench;

abstract readonly class DocBlockParserBench
{
    protected const string DOC_BLOCK_SAMPLE = <<<'DOC'
        /**
         * The "`@link`" tag can be used to define a relation, or link, between
         * the element, or part of the long description when used inline, to a URI.
         *
         * ```
         * "@link" [<URI> | <reference>] [<description>]
         * ```
         *
         * @link https://www.ietf.org/rfc/rfc2396.txt RFC2396
         * @return iterable<string, array{
         *     CommentParserInterface,
         *     string,
         *     list<array{ string, int<0, max> }>
         * }>
         */
        DOC;

    abstract public function benchParseDocBlock(): void;
}
