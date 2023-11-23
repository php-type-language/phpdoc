<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
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

        try {
            $this->assertDocBlockNotContainsInvalidTags($phpdoc, self::NOT_IMPLEMENTED_TAGS, $comment);
        } catch (\Throwable $e) {
            dump($phpdoc);
            throw $e;
        }
    }
}
