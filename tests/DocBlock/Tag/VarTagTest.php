<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTag;
use TypeLang\Type\NamedTypeNode;

final class VarTagTest extends TagTestCase
{
    #[Test]
    public function parsesTypeOnly(): void
    {
        $tag = self::parseTag('@var non-empty-string');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertSame('var', $tag->name);
        self::assertNull($tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@var non-empty-string', (string) $tag);
    }

    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::parseTag('@var list<int> $items The queued items.');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertSame('items', $tag->variable);
        self::assertSame('The queued items.', (string) $tag->description);
        self::assertSame('@var list<int> $items The queued items.', (string) $tag);
    }

    #[Test]
    public function parsesTypeAndDescriptionWithoutVariable(): void
    {
        $tag = self::parseTag('@var int The counter.');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertNull($tag->variable);
        self::assertSame('The counter.', (string) $tag->description);
        self::assertSame('@var int The counter.', (string) $tag);
    }

    #[Test]
    public function rejectsMissingType(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@var'));
    }

    #[Test]
    public function rejectsVariableWithoutType(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@var $items'));
    }
}
