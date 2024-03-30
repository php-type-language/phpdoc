<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Comment;

final class RegexCommentParser implements CommentParserInterface
{
    /**
     * @var non-empty-string
     */
    private const TOKENS_PCRE = '/\G(?'
        . '|(?:(?:^\h*\/\*\*\h*)(*MARK:T_COMMENT_START))'
        . '|(?:(?:\h*\*\/)(*MARK:T_COMMENT_END))'
        . '|(?:(?:^\h*\*\h*)(*MARK:T_COMMENT_PREFIX))'
        . '|(?:(?:\r\n|\n)(*MARK:T_NEWLINE))'
        . '|(?:(?:.+?(?:\r\n|\n|$))(*MARK:T_TEXT))'
        . ')/Ssum';

    /**
     * @var list<non-empty-string>
     */
    private const LEXER_TOKENS_OUTPUT = [
        'T_TEXT',
        'T_NEWLINE',
    ];

    private function isWrappedComment(string $docblock): bool
    {
        if ($docblock === '') {
            return false;
        }

        $offset = \strpos($docblock, '/**');

        // In case of DocBlock:
        return match ($offset) {
            // - Starts with "/**".
            0 => true,
            // - Does not contain "/**" sequence.
            false => false,
            // - Starts with whitespace chars before "/**" sequence.
            default => \ltrim(\substr($docblock, 0, $offset)) === '',
        };
    }

    /**
     * @return iterable<array-key, Segment>
     */
    private function readWrappedComment(string $docblock): iterable
    {
        foreach ($this->lex($docblock) as $offset => $content) {
            yield new Segment($content, $offset);
        }
    }

    /**
     * @return iterable<int<0, max>, string>
     */
    private function lex(string $docblock): iterable
    {
        \preg_match_all(self::TOKENS_PCRE, $docblock, $matches, \PREG_SET_ORDER);

        $offset = 0;

        foreach ($matches as $token) {
            $isSignificant = \in_array($token['MARK'], self::LEXER_TOKENS_OUTPUT, true)
                && \trim($token[0]) !== '';

            if ($isSignificant) {
                yield $offset => $token[0];
            }

            $offset += \strlen($token[0]);
        }
    }

    /**
     * Returns significant parts of the DocBlock comment with their offsets of
     * the returned section, relative to the beginning.
     *
     * ```php
     * $result = $reader->read(<<<'DOC'
     *      /**
     *       * Example line 1
     *       *
     *       * @tag1 type Description of tag1
     *       *∕
     *      DOC);
     *
     * // The $result contains:
     * // object(Segment) { offset: 7, text: 'Example line 1' }
     * // object(Segment) { offset: 28, text: '@tag1 type Description of tag1' }
     * ```
     *
     * @return iterable<array-key, Segment>
     */
    public function parse(string $docblock): iterable
    {
        if ($this->isWrappedComment($docblock)) {
            return $this->readWrappedComment($docblock);
        }

        return [new Segment($docblock)];
    }
}
