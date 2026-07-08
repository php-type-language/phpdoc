<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag\StaticVarTag;
use TypeLang\Type\NamedTypeNode;

final class StaticVarTagTest extends TagTestCase
{
    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::parseTag('@staticvar int $counter The call count.');

        self::assertInstanceOf(StaticVarTag::class, $tag);
        self::assertSame('staticvar', $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('int', (string) $tag->type->name);
        self::assertSame('counter', $tag->variable);
        self::assertSame('The call count.', (string) $tag->description);
        self::assertSame('@staticvar int $counter The call count.', (string) $tag);
    }

    #[Test]
    public function parsesTypeOnly(): void
    {
        $tag = self::parseTag('@staticvar string');

        self::assertInstanceOf(StaticVarTag::class, $tag);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('string', (string) $tag->type->name);
        self::assertNull($tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@staticvar string', (string) $tag);
    }
}
