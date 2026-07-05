<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

use TypeLang\PhpDoc\DocBlock\DocBlock;

/**
 * This interface is responsible for reading significant
 * sections of the {@see DocBlock} comment.
 */
interface SplitterInterface
{
    /**
     * Returns significant parts of the DocBlock comment with their offsets of
     * the returned section, relative to the beginning.
     *
     * ```
     * $result = $parser->parse(<<<'DOC'
     *      /**
     *       * Example line 1
     *       *
     *       * @​tag1 type Description of tag1
     *       *​/
     *      DOC);
     *
     * // The $result contains:
     * // - Segment{ offset: 7, text: 'Example line 1' }
     * // - Segment{ offset: 28, text: '@tag1 type Description of tag1' }
     * ```
     *
     * @return iterable<array-key, Segment>
     */
    public function split(string $docblock): iterable;
}
