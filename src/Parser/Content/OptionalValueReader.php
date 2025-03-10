<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

/**
 * @template T of non-empty-string
 * @template-implements OptionalReaderInterface<T|null>
 */
final class OptionalValueReader implements OptionalReaderInterface
{
    public function __construct(
        /**
         * @var T
         */
        private readonly string $value,
    ) {}

    public function __invoke(Stream $stream): ?string
    {
        if (!\str_starts_with($stream->value, $this->value)) {
            return null;
        }

        $expectedLength = \strlen($this->value);

        // In case of end of string
        if ($expectedLength === \strlen(\rtrim($stream->value))) {
            return $this->apply($stream);
        }

        // In case of separated by whitespace
        if (\ctype_space($stream->value[$expectedLength])) {
            return $this->apply($stream);
        }

        return null;
    }

    /**
     * @return T
     */
    private function apply(Stream $lexer): string
    {
        $lexer->shift(\strlen($this->value));

        return $this->value;
    }
}
