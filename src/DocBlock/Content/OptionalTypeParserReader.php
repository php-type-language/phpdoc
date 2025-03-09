<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Content;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-extends Reader<TypeStatement|null>
 */
final class OptionalTypeParserReader extends Reader
{
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
