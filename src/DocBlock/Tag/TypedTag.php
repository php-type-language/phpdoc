<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

abstract class TypedTag extends Tag implements TypeProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        protected readonly TypeStatement $type,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): TypeStatement
    {
        return $this->type;
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        return \rtrim(\vsprintf('@%s %s %s', [
            $this->name,
            \property_exists($this->type, 'name')
                ? (string)$this->type->name
                : $this->type::class,
            (string)$this->description,
        ]));
    }
}
