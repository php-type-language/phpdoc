<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

final class TemplateTag extends Tag implements OptionalTypeProviderInterface
{
    /**
     * @param non-empty-string $alias
     * @param TypeStatement|null $type
     * @param \Stringable|string|null $description
     */
    public function __construct(
        private readonly string $alias,
        private readonly ?TypeStatement $type = null,
        \Stringable|string|null $description = null
    ) {
        parent::__construct('template', $description);
    }

    /**
     * @return non-empty-string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getType(): ?TypeStatement
    {
        return $this->type;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     alias: non-empty-string,
     *     type: null|array{
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
            'alias' => $this->alias,
            'type' => $this->type?->toArray(),
        ];
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        if ($this->type === null) {
            return \rtrim(\vsprintf('@%s %s %s', [
                $this->name,
                $this->alias,
                (string)$this->description,
            ]));
        }

        return \rtrim(\vsprintf('@%s %s of %s %s', [
            $this->name,
            $this->alias,
            TypedTag::getTypeAsString($this->type),
            (string)$this->description,
        ]));
    }
}
