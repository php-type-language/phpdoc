<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/property.html#property-property-read-property-write
 */
final class PropertyReadTag extends TypedTag implements CreatableFromNameTypeAndDescriptionInterface
{
    /**
     * @param non-empty-string $variable
     */
    public function __construct(
        private readonly string $variable,
        TypeStatement $type,
        \Stringable|string|null $description = null
    ) {
        parent::__construct('property-read', $type, $description);
    }

    public static function createFromNameTypeAndDescription(
        string $name,
        TypeStatement $type,
        \Stringable|string|null $description = null,
    ): self {
        return new self($name, $type, $description);
    }

    /**
     * @return non-empty-string
     */
    public function getVarName(): string
    {
        return $this->variable;
    }
}
