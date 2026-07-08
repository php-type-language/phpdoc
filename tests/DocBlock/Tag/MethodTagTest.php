<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTag;
use TypeLang\Type\NamedTypeNode;

final class MethodTagTest extends TagTestCase
{
    #[Test]
    public function parsesCallableCarryingItsOwnReturnType(): void
    {
        $tag = self::parseTag('@method foo(T $t): void An optional description.');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('method', $tag->name);
        self::assertSame('foo', (string) $tag->method->name);
        self::assertFalse($tag->isStatic);
        self::assertNotNull($tag->method->type);
        self::assertSame('An optional description.', (string) $tag->description);
        self::assertSame('@method foo(T $t): void An optional description.', (string) $tag);
    }

    #[Test]
    public function parsesLeadingReturnTypeAndStatic(): void
    {
        $tag = self::parseTag('@method static ReturnType bar(U $u) An optional description.');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('bar', (string) $tag->method->name);
        self::assertTrue($tag->isStatic);
        // The leading return type is grafted onto the callable.
        self::assertInstanceOf(NamedTypeNode::class, $tag->method->type);
        self::assertSame('ReturnType', (string) $tag->method->type->name);
        self::assertSame('@method static ReturnType bar(U $u) An optional description.', (string) $tag);
    }

    #[Test]
    public function parsesInstanceMethodWithoutDescription(): void
    {
        $tag = self::parseTag('@method self withValue(mixed $value)');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('withValue', (string) $tag->method->name);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    #[Test]
    public function rejectsNonCallableSignature(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@method JustAType'));
    }
}
