<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

/**
 * @link https://php.watch/articles/no-named-arguments-docblock-attribute
 * @link https://youtrack.jetbrains.com/issue/WI-56969/PHP-8.0-named-parameter-no-named-arguments-support-request
 * @link https://psalm.dev/docs/running_psalm/issues/NamedArgumentNotAllowed/
 * @link https://github.com/phpstan/phpstan-src/pull/1349
 */
final class NoNamedArgumentsTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('no-named-arguments', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
