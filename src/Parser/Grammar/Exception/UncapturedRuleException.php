<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Exception;

final class UncapturedRuleException extends GrammarException
{
    public function __construct(
        public string $name,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function becauseValueNotCaptured(string $name, ?\Throwable $previous = null): self
    {
        $message = \sprintf('No value captured under "%s"', $name);

        return new self($name, $message, 0, $previous);
    }
}
