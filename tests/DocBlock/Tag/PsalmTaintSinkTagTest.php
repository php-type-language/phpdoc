<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSinkTag\PsalmTaintSinkTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;

final class PsalmTaintSinkTagTest extends TestCase
{
    #[Test]
    public function parsesNameAndVariable(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-taint-sink html $output */');

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(PsalmTaintSinkTag::class, $block->tags[0]);
        self::assertSame('psalm-taint-sink', $block->tags[0]->name);
        self::assertSame('html', $block->tags[0]->taint);
        self::assertSame('output', $block->tags[0]->variable);
        self::assertNull($block->tags[0]->description);
        self::assertSame('@psalm-taint-sink html $output', (string) $block->tags[0]);
    }
}
