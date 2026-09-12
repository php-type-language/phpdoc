<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Reference\ClassPropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableMethodReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariablePropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableReference;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag\AssertIfFalseTag;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag\AssertIfTrueTag;
use TypeLang\PhpDoc\DocBlock\Tag\AssertionTag;
use TypeLang\PhpDoc\DocBlock\Tag\AssertOperator;
use TypeLang\PhpDoc\DocBlock\Tag\AssertTag\AssertTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTagInterface;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\Type\NamedTypeNode;

final class AssertionTagTest extends TagTestCase
{
    #[Test]
    public function parsesTypeSubjectAndDescription(): void
    {
        $tag = self::parseTag('@phpstan-assert non-empty-string $name The name it carries.');

        self::assertInstanceOf(AssertTag::class, $tag);
        self::assertInstanceOf(AssertionTag::class, $tag);
        self::assertInstanceOf(TypedTagInterface::class, $tag);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame(AssertOperator::Is, $tag->operator);
        self::assertInstanceOf(VariableReference::class, $tag->subject);
        self::assertSame('name', $tag->subject->name);
        self::assertSame('The name it carries.', (string) $tag->description);
        self::assertSame('@phpstan-assert non-empty-string $name The name it carries.', (string) $tag);
    }

    /**
     * @param non-empty-string $tag
     */
    #[Test]
    #[DataProvider('operatorProvider')]
    public function readsThePrefixOfTheType(string $tag, AssertOperator $expected): void
    {
        $result = self::parseTag($tag);

        self::assertInstanceOf(AssertionTag::class, $result);
        self::assertSame($expected, $result->operator);
        self::assertSame($tag, (string) $result);
    }

    /**
     * @return iterable<string, array{non-empty-string, AssertOperator}>
     */
    public static function operatorProvider(): iterable
    {
        yield 'of the type' => ['@phpstan-assert int $value', AssertOperator::Is];
        yield 'anything but the type' => ['@phpstan-assert !null $value', AssertOperator::IsNot];
        yield 'the very value' => ['@phpstan-assert =non-empty-string $value', AssertOperator::Equals];
        yield 'anything but the value' => ['@phpstan-assert !=null $value', AssertOperator::NotEquals];

        // The prefixes are read by every member of the family alike
        yield 'when the call returns true' => ['@phpstan-assert-if-true !false $value', AssertOperator::IsNot];
        yield 'when the call returns false' => ['@psalm-assert-if-false =int $value', AssertOperator::Equals];
    }

    /**
     * @param non-empty-string $tag
     * @param class-string $expected
     */
    #[Test]
    #[DataProvider('subjectProvider')]
    public function readsWhatTheAssertionIsWrittenOf(string $tag, string $expected, string $printed): void
    {
        $result = self::parseTag($tag);

        self::assertInstanceOf(AssertionTag::class, $result);
        self::assertInstanceOf($expected, $result->subject);
        self::assertSame($printed, (string) $result->subject);
        self::assertSame($tag, (string) $result);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string, non-empty-string}>
     */
    public static function subjectProvider(): iterable
    {
        yield 'a variable' => [
            '@phpstan-assert int $value',
            VariableReference::class,
            '$value',
        ];
        yield 'a property of a variable' => [
            '@phpstan-assert-if-true !null $this->comparisonFailure',
            VariablePropertyReference::class,
            '$this->comparisonFailure',
        ];
        yield 'a method of a variable' => [
            '@phpstan-assert Some\Any $this->getItems()',
            VariableMethodReference::class,
            '$this->getItems()',
        ];
        yield 'a static property of a class' => [
            '@psalm-assert !null self::$instances',
            ClassPropertyReference::class,
            'self::$instances',
        ];
    }

    /**
     * @param non-empty-string $name
     * @param class-string<AssertionTag> $expected
     */
    #[Test]
    #[DataProvider('assertionTagProvider')]
    public function assertionTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s int $x', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(AssertionTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<AssertionTag>}>
     */
    public static function assertionTagProvider(): iterable
    {
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

    /**
     * @param non-empty-string $tag
     */
    #[Test]
    #[DataProvider('malformedProvider')]
    public function rejectsWhatIsNoAssertion(string $tag): void
    {
        $result = self::parseTag($tag);

        self::assertInstanceOf(InvalidTag::class, $result);
        self::assertInstanceOf(MalformedTagException::class, $result->reason);
    }

    /**
     * @return iterable<string, array{non-empty-string}>
     */
    public static function malformedProvider(): iterable
    {
        yield 'a type alone' => ['@phpstan-assert int'];
        yield 'a subject alone' => ['@phpstan-assert $value'];
        yield 'a prefix alone' => ['@phpstan-assert !'];
        yield 'a property of nothing' => ['@phpstan-assert int $this->'];
        yield 'a class of no property' => ['@phpstan-assert int self::'];
    }
}
