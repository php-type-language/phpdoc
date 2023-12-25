<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/global.html#global
 */
final class GlobalTag extends TypedTag implements CreatableFromNameTypeAndDescriptionInterface
{
    /**
     * @param non-empty-string $variable
     */
    public function __construct(
        private readonly string $variable,
        TypeStatement $type,
        \Stringable|string|null $description = null
    ) {
        parent::__construct('global', $type, $description);
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     variable: non-empty-string,
     *     type: array{
     *         kind: int<0, max>,
     *         ...
     *     },
     *     description?: array{
     *         template: string,
     *         tags: list<array>
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'variable' => $this->variable,
            'type' => $this->type->toArray(),
        ];
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
