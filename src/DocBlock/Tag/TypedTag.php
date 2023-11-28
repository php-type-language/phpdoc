<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Printer\PrettyPrinter;

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
        $type = $this->type::class;

        /** @psalm-suppress UndefinedPropertyFetch : Psalm false-positive */
        if (\property_exists($this->type, 'name')
            && $this->type->name instanceof Name) {
            $type = (string)$this->type->name;
        }

        if (\class_exists(PrettyPrinter::class)) {
            $type = (new PrettyPrinter())->print($this->type);
        }

        return \rtrim(\vsprintf('@%s %s %s', [
            $this->name,
            $type,
            (string)$this->description,
        ]));
    }
}
