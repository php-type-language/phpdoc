<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-implements ReaderInterface<TypeStatement>
 */
final class TypeReader implements ReaderInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser,
    ) {}

    /**
     * @throws \Throwable
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): TypeStatement
    {
        try {
            $type = $this->parser->parse($stream->value);
        } catch (ParserExceptionInterface $e) {
            // @phpstan-ignore-next-line : Property is defined
            if (\trim($stream->value) === '') {
                throw $stream->toException(\sprintf(
                    'Tag @%s expects the type to be defined',
                    $stream->tag,
                ), $e);
            }

            throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect type "%s"',
                $stream->tag,
                \trim($stream->value),
            ), $e);
        }

        // @phpstan-ignore-next-line : Property is defined
        $stream->shift($this->parser->lastProcessedTokenOffset);

        return $type;
    }
}
