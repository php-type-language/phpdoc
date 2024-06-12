<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Tag\Content;

/**
 * @template-extends Applicator<TypeStatement>
 */
final class TypeParserApplicator extends Applicator
{
    /**
     * @param non-empty-string $tag
     */
    public function __construct(
        private readonly string $tag,
        private readonly TypesParserInterface $parser,
    ) {}

    /**
     * @throws \Throwable
     * @throws InvalidTagException
     */
    public function __invoke(Content $lexer): TypeStatement
    {
        try {
            $type = $this->parser->parse($lexer->value);
        } catch (ParserExceptionInterface $e) {
            /** @psalm-suppress InvalidArgument */
            throw $lexer->getTagException(
                message: \sprintf('Tag @%s contains an incorrect type', $this->tag),
                previous: $e,
            );
        }

        // @phpstan-ignore-next-line : Property is defined
        $lexer->shift($this->parser->lastProcessedTokenOffset);

        return $type;
    }
}
