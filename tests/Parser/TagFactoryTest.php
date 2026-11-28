<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\FinalTag\FinalTag;
use TypeLang\PhpDoc\DocBlock\Tag\FinalTag\FinalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\GenericTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorForDefinitionException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class TagFactoryTest extends TestCase
{
    public function testUnknownTagKeepsItsWrittenName(): void
    {
        $factory = new TagFactory(new TagRegistry(), self::combinators());

        $tag = $factory->create('Custom-Tag', '');

        self::assertSame(Tag::class, $tag::class);
        self::assertSame('Custom-Tag', $tag->name);
    }

    public function testDefinitionOfUnknownTagHasNoDescription(): void
    {
        self::assertNull((new GenericTagDefinition('custom-tag'))->description);
    }

    public function testUnknownTagDefinitionIsCreatedByLowerCaseName(): void
    {
        $names = [];

        $factory = new TagFactory(
            registry: new TagRegistry(),
            combinators: self::combinators(),
            createUnknownTagDefinition: static function (string $name) use (&$names): TagDefinitionInterface {
                $names[] = $name;

                return new FinalTagDefinition();
            },
        );

        $tag = $factory->create('Custom-Tag', '');

        self::assertInstanceOf(FinalTag::class, $tag);
        self::assertSame(['custom-tag'], $names);
    }

    public function testRegisteredTagIsNotReadByUnknownTagDefinition(): void
    {
        $factory = new TagFactory(
            registry: new TagRegistry(['final' => new FinalTagDefinition()]),
            combinators: self::combinators(),
            createUnknownTagDefinition: static function (): never {
                throw new \LogicException('Must not be called');
            },
        );

        self::assertInstanceOf(FinalTag::class, $factory->create('final', ''));
    }

    public function testMalformedBodyIsAnInvalidTag(): void
    {
        $factory = new TagFactory(
            registry: new TagRegistry(['final' => new FinalTagDefinition()]),
            combinators: self::combinators(),
        );

        $tag = $factory->create('final', 'unexpected');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    public function testUnknownCombinatorIsThrown(): void
    {
        $factory = new TagFactory(new TagRegistry(['final' => new FinalTagDefinition()]));

        $this->expectException(InvalidCombinatorForDefinitionException::class);

        $factory->create('final', 'some description');
    }

    public function testErrorOfCombinatorIsThrown(): void
    {
        $factory = new TagFactory(
            registry: new TagRegistry(['final' => new FinalTagDefinition()]),
            combinators: [
                DescriptionCombinator::NAME => static function (): never {
                    throw new \Error('Broken combinator');
                },
            ],
        );

        $this->expectExceptionObject(new \Error('Broken combinator'));

        $factory->create('final', 'some description');
    }

    /**
     * Combinators of the tags with an empty body: the description is absent.
     *
     * @return array<non-empty-string, \Closure(): never>
     */
    private static function combinators(): array
    {
        return [
            DescriptionCombinator::NAME => static function (): never {
                throw new NoMatchException('No description');
            },
        ];
    }
}
