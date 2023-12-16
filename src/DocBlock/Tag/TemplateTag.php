<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

final class TemplateTag extends Tag implements OptionalTypeProviderInterface
{
    /**
     * @param non-empty-string $typeName
     * @param TypeStatement|null $type
     * @param \Stringable|string|null $description
     */
    public function __construct(
        private readonly string $typeName,
        private readonly ?TypeStatement $type = null,
        \Stringable|string|null $description = null
    ) {
        parent::__construct('template', $description);
    }

    /**
     * @return non-empty-string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function getType(): ?TypeStatement
    {
        return $this->type;
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        if ($this->type === null) {
            return \rtrim(\vsprintf('@%s %s %s', [
                $this->name,
                $this->typeName,
                (string)$this->description,
            ]));
        }

        return \rtrim(\vsprintf('@%s %s of %s %s', [
            $this->name,
            $this->typeName,
            TypedTag::getTypeAsString($this->type),
            (string)$this->description,
        ]));
    }
}
