<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

/**
 * TODO This tag doesnt support description: Should support for this
 *      functionality be removed?
 *
 * @link https://phpstan.org/writing-php-code/phpdocs-basics#immutable-classes
 * @link https://psalm.dev/docs/annotating_code/supported_annotations/#psalm-immutable
 */
final class ImmutableTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('immutable', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
