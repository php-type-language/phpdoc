<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-implements OptionalReaderInterface<TypeStatement>
 */
final class OptionalTypeReader implements OptionalReaderInterface
{
    private const IS_SIMPLE_TYPE_PCRE = '/^[a-zA-Z_\\x80-\\xff][a-zA-Z0-9\\-_\\x80-\\xff]*+(?:\s|$)/Ssu';

    public function __construct(
        private readonly TypesParserInterface $parser,
    ) {}

    /**
     * @throws \Throwable
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): ?TypeStatement
    {
        try {
            $type = $this->parser->parse($stream->value);
        } catch (ParserExceptionInterface) {
            return null;
        }

        // @phpstan-ignore-next-line : Property is defined
        $stream->shift($this->parser->lastProcessedTokenOffset);

        return $type;
    }
}
