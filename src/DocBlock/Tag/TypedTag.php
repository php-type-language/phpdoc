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
     * @return non-empty-string
     */
    public static function getTypeAsString(TypeStatement $type): string
    {
        if (\class_exists(PrettyPrinter::class)) {
            return (new PrettyPrinter())->print($type);
        }

        /** @psalm-suppress UndefinedPropertyFetch : Psalm false-positive */
        if (\property_exists($type, 'name') && $type->name instanceof Name) {
            return (string)$type->name;
        }

        return $type::class;
    }

    /**
     * @return array{
     *     name: non-empty-string,
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
            'type' => $this->type->toArray(),
        ];
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        return \rtrim(\vsprintf('@%s %s %s', [
            $this->name,
            self::getTypeAsString($this->type),
            (string)$this->description,
        ]));
    }
}
