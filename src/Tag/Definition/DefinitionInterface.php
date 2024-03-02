<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Definition;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Platform\PlatformVersionProviderInterface;

interface DefinitionInterface extends
    PlatformVersionProviderInterface,
    SerializableInterface
{
    /**
     * Returns the name of the tag definition.
     *
     * @return non-empty-string
     *
     * @psalm-immutable
     */
    public function getName(): string;

    /**
     * Returns description of the tag definition.
     *
     * @psalm-immutable
     */
    public function getDescription(): \Stringable|string|null;
}
