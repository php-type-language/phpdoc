<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmAssertUntaintedTag\PsalmAssertUntaintedTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTraceTag\PsalmTraceTag;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\VariableTagInterface;
use TypeLang\PhpDoc\Exception\MalformedTagException;

final class VariableTagTest extends TagTestCase
{
    #[Test]
    public function parsesVariableWithDescription(): void
    {
        $tag = self::parseTag('@param-immediately-invoked-callable $callback Runs during the call.');

        self::assertInstanceOf(ParamImmediatelyInvokedCallableTag::class, $tag);
        self::assertInstanceOf(VariableTagInterface::class, $tag);
        self::assertSame('param-immediately-invoked-callable', $tag->name);
        self::assertSame('callback', $tag->variable);
        self::assertSame('Runs during the call.', (string) $tag->description);
        self::assertSame('@param-immediately-invoked-callable $callback Runs during the call.', (string) $tag);
    }

    #[Test]
    public function parsesVariableWithoutDescription(): void
    {
        $tag = self::parseTag('@unused-param $context');

        self::assertInstanceOf(UnusedParamTag::class, $tag);
        self::assertSame('context', $tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@unused-param $context', (string) $tag);
    }

    #[Test]
    public function invokedCallableTagsShareACommonBase(): void
    {
        $immediately = self::parseTag('@param-immediately-invoked-callable $a');
        $later = self::parseTag('@param-later-invoked-callable $b');

        self::assertInstanceOf(ParamInvokedCallableTag::class, $immediately);
        self::assertInstanceOf(ParamInvokedCallableTag::class, $later);
        self::assertInstanceOf(ParamLaterInvokedCallableTag::class, $later);
    }

    #[Test]
    public function rejectsMissingVariable(): void
    {
        $tag = self::parseTag('@unused-param');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    /**
     * @param class-string<VariableTagInterface> $expected
     */
    #[Test]
    #[DataProvider('variableTagProvider')]
    public function variableTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s $x', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(VariableTagInterface::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('x', $tag->variable);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<VariableTagInterface>}>
     */
    public static function variableTagProvider(): iterable
    {
        yield '@param-immediately-invoked-callable' => [
            'param-immediately-invoked-callable',
            ParamImmediatelyInvokedCallableTag::class,
        ];
        yield '@param-later-invoked-callable' => [
            'param-later-invoked-callable',
            ParamLaterInvokedCallableTag::class,
        ];
        yield '@unused-param' => ['unused-param', UnusedParamTag::class];
        yield '@psalm-assert-untainted' => ['psalm-assert-untainted', PsalmAssertUntaintedTag::class];
        yield '@psalm-trace' => ['psalm-trace', PsalmTraceTag::class];
    }
}
