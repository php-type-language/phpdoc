<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class UnknownTagAliasException extends InvalidTagAliasException
{
    /**
     * Occurs when a registered tag alias cannot be resolved to any
     * known tag definition.
     *
     * @param non-empty-string $alias
     * @param list<non-empty-string> $path chain of visited aliases
     */
    public static function becauseAliasHasNoDefinition(string $alias, array $path, ?\Throwable $prev = null): self
    {
        $message = 'Unable to find tag definition for "%s" tag alias in alias chain [%s]';
        $pathAsString = \implode(' > ', [$alias, ...$path]);

        return new self(\sprintf($message, $alias, $pathAsString), previous: $prev);
    }
}
