<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\FlowType;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmFlowTag\PsalmFlowTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;

final class PsalmFlowTagTest extends TestCase
{
    #[Test]
    public function parsesFlowTypeWithVariable(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-flow TaintSource $data */');

        self::assertInstanceOf(PsalmFlowTag::class, $block->tags[0]);
        self::assertSame('psalm-flow', $block->tags[0]->name);
        self::assertSame(FlowType::TaintSource, $block->tags[0]->flow);
        self::assertSame('data', $block->tags[0]->variable);
        self::assertSame('@psalm-flow TaintSource $data', (string) $block->tags[0]);
    }

    #[Test]
    public function parsesFlowTypeWithoutVariable(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-flow TaintSink */');

        self::assertInstanceOf(PsalmFlowTag::class, $block->tags[0]);
        self::assertSame(FlowType::TaintSink, $block->tags[0]->flow);
        self::assertNull($block->tags[0]->variable);
        self::assertSame('@psalm-flow TaintSink', (string) $block->tags[0]);
    }

    #[Test]
    public function unknownFlowTypeProducesInvalidTag(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-flow NotAFlow */');

        self::assertInstanceOf(InvalidTag::class, $block->tags[0]);
    }
}
