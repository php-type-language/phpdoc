<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Grammar;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\ClassConstantReference;
use TypeLang\PhpDoc\DocBlock\Reference\ClassMethodReference;
use TypeLang\PhpDoc\DocBlock\Reference\ClassPropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\FunctionReference;
use TypeLang\PhpDoc\DocBlock\Reference\ReferenceInterface;
use TypeLang\PhpDoc\DocBlock\Reference\SymbolReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

final class ReferenceGrammarRuleTest extends GrammarRuleTestCase
{
    protected function rule(): ReferenceCombinator
    {
        return new ReferenceCombinator();
    }

    /**
     * Each reference, mapped to the expected reference class and the string it
     * stringifies back to.
     *
     * @return iterable<string, array{string, class-string<ReferenceInterface>, string}>
     */
    public static function referenceDataProvider(): iterable
    {
        yield 'class' => ['Some\Any\Ololo', SymbolReference::class, 'Some\Any\Ololo'];
        yield 'function' => ['Some\Any\foo()', FunctionReference::class, 'Some\Any\foo()'];
        yield 'class method' => ['Some\Any\Ololo::foo()', ClassMethodReference::class, 'Some\Any\Ololo::foo()'];
        yield 'class constant' => ['Some\Any\Ololo::CONST_EXAMPLE', ClassConstantReference::class, 'Some\Any\Ololo::CONST_EXAMPLE'];
        yield 'class property' => ['Some\Any::$var', ClassPropertyReference::class, 'Some\Any::$var'];
        yield 'variable' => ['$var', VariableReference::class, '$var'];
        yield 'unicode class' => ['Мой\Класс', SymbolReference::class, 'Мой\Класс'];
    }

    /**
     * @param class-string<ReferenceInterface> $expected
     */
    #[Test]
    #[DataProvider('referenceDataProvider')]
    public function producesTheExpectedReference(string $input, string $expected, string $stringified): void
    {
        $reference = $this->matchText($input);

        self::assertInstanceOf($expected, $reference);
        self::assertSame($stringified, (string) $reference);
    }

    #[Test]
    public function classMethodExposesClassAndName(): void
    {
        $reference = $this->matchText('Some\Any\Ololo::foo()');

        self::assertInstanceOf(ClassMethodReference::class, $reference);
        self::assertSame('Some\Any\Ololo', $reference->class);
        self::assertSame('foo', $reference->name);
    }

    /**
     * Only the reference is consumed, the rest stays for the next rule.
     */
    #[Test]
    public function stopsAtTheFirstWhitespace(): void
    {
        $cursor = new Cursor('$var and the rest');
        $reference = $this->matchCursor($cursor);

        self::assertInstanceOf(VariableReference::class, $reference);
        self::assertSame('var', $reference->name);
        self::assertSame(4, $cursor->offset);
    }

    /**
     * A multi-part reference is consumed up to its last token, leaving the
     * trailing text untouched.
     */
    #[Test]
    public function stopsAfterTheReference(): void
    {
        $cursor = new Cursor('Some\Any\Ololo::foo() and the rest');
        $reference = $this->matchCursor($cursor);

        self::assertInstanceOf(ClassMethodReference::class, $reference);
        self::assertSame(21, $cursor->offset);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'whitespace only' => ['   '];
        yield 'illegal character' => ['foo!bar'];
        yield 'trailing after call' => ['foo()bar'];
        yield 'dangling "::"' => ['Some\Any::'];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function rejectsAnInvalidReference(string $input): void
    {
        $this->expectException(NoMatchException::class);

        $this->matchText($input);
    }
}
