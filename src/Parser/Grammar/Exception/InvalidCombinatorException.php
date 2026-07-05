<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Exception;

class InvalidCombinatorException extends GrammarException
{
    final public function __construct(
        public string $name,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function becauseInvalidRule(
        string $name,
        ?\Throwable $previous = null,
    ): self {
        $message = \vsprintf('Tag combinator rule "%s" is not registered', [
            \addcslashes($name, '"'),
        ]);

        return new self($name, $message, 0, $previous);
    }
}
