<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Comment;

use Phplrt\Contracts\Lexer\LexerExceptionInterface;
use Phplrt\Contracts\Lexer\LexerInterface;
use Phplrt\Contracts\Lexer\LexerRuntimeExceptionInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Source\SourceExceptionInterface;
use Phplrt\Contracts\Source\SourceFactoryInterface;
use Phplrt\Lexer\Config\PassthroughHandler;
use Phplrt\Lexer\Lexer;
use Phplrt\Source\SourceFactory;

/**
 * This class is responsible for reading significant
 * sections of the DocBlock comment.
 */
final class LexerAwareCommentParser implements CommentParserInterface
{
    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private const LEXER_TOKENS = [
        'T_COMMENT_START' => '^\h*/\*\*\h*',
        'T_COMMENT_END' => '\h*\*/',
        'T_COMMENT_PREFIX' => '^\h*\*\h*',
        'T_NEWLINE' => '\r\n|\n',
        'T_TEXT' => '.+?(\r\n|\n|$)',
    ];

    /**
     * @var list<non-empty-string>
     */
    private const LEXER_TOKENS_OUTPUT = [
        'T_TEXT',
        'T_NEWLINE',
    ];

    private readonly LexerInterface $lexer;

    public function __construct(
        private readonly SourceFactoryInterface $sources = new SourceFactory(),
    ) {
        $this->lexer = new Lexer(
            tokens: self::LEXER_TOKENS,
            onUnknownToken: new PassthroughHandler(),
        );
    }

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

    private function isSignificantComment(TokenInterface $token): bool
    {
        return \in_array($token->getName(), self::LEXER_TOKENS_OUTPUT, true)
            && \trim($token->getValue()) !== '';
    }

    /**
     * @return iterable<array-key, Segment>
     *
     * @throws LexerExceptionInterface
     * @throws LexerRuntimeExceptionInterface
     * @throws SourceExceptionInterface
     */
    private function readWrappedComment(string $docblock): iterable
    {
        $source = $this->sources->create($docblock);

        foreach ($this->lexer->lex($source) as $token) {
            if ($this->isSignificantComment($token)) {
                yield new Segment($token->getValue(), $token->getOffset());
            }
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
     *
     * @throws LexerExceptionInterface
     * @throws LexerRuntimeExceptionInterface
     * @throws SourceExceptionInterface
     */
    public function parse(string $docblock): iterable
    {
        if ($this->isWrappedComment($docblock)) {
            return $this->readWrappedComment($docblock);
        }

        return [new Segment($docblock)];
    }
}
