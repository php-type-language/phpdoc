<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlockFactory;
use TypeLang\PhpDocParser\DocBlockFactoryInterface;

final class DocBlockParsingTest extends TestCase
{
    public static function docBlockDataProvider(): iterable
    {
        $instance = DocBlockFactory::createInstance();

        foreach (\glob(__DIR__ . '/cases/*.txt') as $file) {
            $name = \basename($file, '.txt');

            yield $name => [$instance, \file_get_contents($file)];
        }
    }

    #[DataProvider('docBlockDataProvider')]
    public function testIsReadable(DocBlockFactoryInterface $factory, string $comment): void
    {
        self::expectNotToPerformAssertions();

        $factory->create($comment);
    }

    #[Depends('testIsReadable')]
    #[DataProvider('docBlockDataProvider')]
    public function testTagIsSupported(DocBlockFactoryInterface $factory, string $comment): void
    {
        $phpdoc = $factory->create($comment);

        $this->assertDescriptionNotContainsInvalidTags($phpdoc->getDescription(), [
        ]);

        // Suppress "no assertions" notice
        self::assertTrue(true);
    }

    /**
     * @param list<non-empty-string> $except
     */
    private function assertDescriptionNotContainsInvalidTags(
        \Stringable|string|null $description,
        array $except = [],
    ): void {
        if ($description instanceof Description) {
            $this->assertTagsNotContainsInvalidTags($description->getTags(), $except);
        }
    }

    /**
     * @param iterable<TagInterface> $tags
     * @param list<non-empty-string> $except
     */
    private function assertTagsNotContainsInvalidTags(iterable $tags, array $except = []): void
    {
        foreach ($tags as $tag) {
            if ($tag instanceof InvalidTag) {
                self::assertContains($tag->getName(), $except);
            }

            $this->assertDescriptionNotContainsInvalidTags($tag->getDescription(), $except);
        }
    }
}
