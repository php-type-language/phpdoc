<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class RecursiveTagAliasException extends InvalidTagAliasException
{
    /**
     * Occurs when a tag alias cannot be resolved to a canonical tag
     * definition because its references form a cycle.
     *
     * @param non-empty-string $alias
     * @param list<non-empty-string> $path chain of aliases visited before the cycle was detected
     */
    public static function becauseAliasIsRecursive(string $alias, array $path, ?\Throwable $prev = null): self
    {
        $message = 'Cannot determine canonical tag definition from alias "%s"'
            . ' because recursive references [%s] were found';
        $pathAsString = \implode(' > ', [...$path, \reset($path)]);

        return new self(\sprintf($message, $alias, $pathAsString), previous: $prev);
    }
}
