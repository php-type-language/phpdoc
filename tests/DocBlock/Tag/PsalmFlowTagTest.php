<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\FlowType;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmFlowTag\PsalmFlowTag;

final class PsalmFlowTagTest extends TagTestCase
{
    #[Test]
    public function parsesFlowTypeWithVariable(): void
    {
        $tag = self::parseTag('@psalm-flow TaintSource $data');

        self::assertInstanceOf(PsalmFlowTag::class, $tag);
        self::assertSame('psalm-flow', $tag->name);
        self::assertSame(FlowType::TaintSource, $tag->flow);
        self::assertSame('data', $tag->variable);
        self::assertSame('@psalm-flow TaintSource $data', (string) $tag);
    }

    #[Test]
    public function parsesFlowTypeWithoutVariable(): void
    {
        $tag = self::parseTag('@psalm-flow TaintSink');

        self::assertInstanceOf(PsalmFlowTag::class, $tag);
        self::assertSame(FlowType::TaintSink, $tag->flow);
        self::assertNull($tag->variable);
        self::assertSame('@psalm-flow TaintSink', (string) $tag);
    }

    #[Test]
    public function rejectsUnknownFlowType(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@psalm-flow NotAFlow'));
    }

    #[Test]
    public function rejectsMissingFlowType(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@psalm-flow'));
    }
}
