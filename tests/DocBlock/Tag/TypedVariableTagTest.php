<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag\AssertIfFalseTag;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag\AssertIfTrueTag;
use TypeLang\PhpDoc\DocBlock\Tag\AssertTag\AssertTag;
use TypeLang\PhpDoc\DocBlock\Tag\GlobalTag\GlobalTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamClosureThisTag\ParamClosureThisTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag\ParamOutTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\MagicPropertyTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTag;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;
use TypeLang\PhpDoc\DocBlock\Tag\VariableTagInterface;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\Type\NamedTypeNode;

final class TypedVariableTagTest extends TagTestCase
{
    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::parseTag('@param non-empty-list<int> $items The collected items.');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertInstanceOf(TypedTagInterface::class, $tag);
        self::assertInstanceOf(VariableTagInterface::class, $tag);
        self::assertSame('param', $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('items', $tag->variable);
        self::assertSame('The collected items.', (string) $tag->description);
        self::assertSame('@param non-empty-list<int> $items The collected items.', (string) $tag);
    }

    #[Test]
    public function parsesTypeAndVariableWithoutDescription(): void
    {
        $tag = self::parseTag('@property-read string $name');

        self::assertInstanceOf(PropertyReadTag::class, $tag);
        self::assertInstanceOf(MagicPropertyTag::class, $tag);
        self::assertSame('name', $tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@property-read string $name', (string) $tag);
    }

    #[Test]
    public function rejectsMissingVariable(): void
    {
        $tag = self::parseTag('@param int');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    /**
     * @param class-string<TypedVariableTag> $expected
     */
    #[Test]
    #[DataProvider('typedVariableTagProvider')]
    public function typedVariableTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s int $x', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(TypedVariableTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('x', $tag->variable);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<TypedVariableTag>}>
     */
    public static function typedVariableTagProvider(): iterable
    {
        yield '@param' => ['param', ParamTag::class];
        yield '@param-out' => ['param-out', ParamOutTag::class];
        yield '@param-closure-this' => ['param-closure-this', ParamClosureThisTag::class];
        yield '@property' => ['property', PropertyTag::class];
        yield '@property-read' => ['property-read', PropertyReadTag::class];
        yield '@property-write' => ['property-write', PropertyWriteTag::class];
        yield '@global' => ['global', GlobalTag::class];

        // The assert family is shared across Psalm, PHPStan and Phan, each
        // contributing it under its own vendor-prefixed name.
        yield '@psalm-assert' => ['psalm-assert', AssertTag::class];
        yield '@phpstan-assert' => ['phpstan-assert', AssertTag::class];
        yield '@phan-assert' => ['phan-assert', AssertTag::class];
        yield '@psalm-assert-if-true' => ['psalm-assert-if-true', AssertIfTrueTag::class];
        yield '@phpstan-assert-if-true' => ['phpstan-assert-if-true', AssertIfTrueTag::class];
        yield '@phan-assert-if-true' => ['phan-assert-if-true', AssertIfTrueTag::class];
        yield '@psalm-assert-if-false' => ['psalm-assert-if-false', AssertIfFalseTag::class];
        yield '@phpstan-assert-if-false' => ['phpstan-assert-if-false', AssertIfFalseTag::class];
        yield '@phan-assert-if-false' => ['phan-assert-if-false', AssertIfFalseTag::class];
    }
}
