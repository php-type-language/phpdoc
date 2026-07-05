<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Exception;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

final class InvalidCombinatorForDefinitionException extends InvalidCombinatorException
{
    public static function becauseInvalidRuleForDefinition(
        string $name,
        TagDefinitionInterface $definition,
        ?\Throwable $previous = null,
    ): self {
        $message = \vsprintf('Could not parse "%s", because combinator rule "%s" is not registered', [
            \addcslashes((string) $definition, '"'),
            \addcslashes($name, '"'),
        ]);

        return new self($name, $message, 0, $previous);
    }
}
