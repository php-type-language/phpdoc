<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Comment;

/**
 * This interface is responsible for reading significant
 * sections of the DocBlock comment.
 *
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc
 */
interface CommentParserInterface
{
    /**
     * Returns significant parts of the DocBlock comment with their offsets of
     * the returned section, relative to the beginning.
     *
     * ```php
     * $result = $parser->parse(<<<'DOC'
     *      /**
     *       * Example line 1
     *       *
     *       * @tag1 type Description of tag1
     *       *∕
     *      DOC);
     *
     * // The $result contains:
     * // 7 => 'Example line 1'
     * // 28 => '@tag1 type Description of tag1'
     * ```
     *
     * @return iterable<array-key, Segment>
     */
    public function parse(string $docblock): iterable;
}
