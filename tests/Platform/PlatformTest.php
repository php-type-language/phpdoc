<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Platform;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorForDefinitionException;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Platform\PhanPlatform;
use TypeLang\PhpDoc\Platform\PhpCodeSnifferPlatform;
use TypeLang\PhpDoc\Platform\PhpDocumentorPlatform;
use TypeLang\PhpDoc\Platform\PhpStanPlatform;
use TypeLang\PhpDoc\Platform\PhpStormPlatform;
use TypeLang\PhpDoc\Platform\Platform;
use TypeLang\PhpDoc\Platform\PlatformInterface;
use TypeLang\PhpDoc\Platform\PsalmPlatform;
use TypeLang\PhpDoc\Platform\StandardPlatform;
use TypeLang\PhpDoc\Tests\TestCase;

final class PlatformTest extends TestCase
{
    #[DataProvider('platformDataProvider')]
    public function testPlatformAloneBuildsParser(PlatformInterface $platform): void
    {
        $parser = new DocBlockParser([$platform]);

        foreach ($platform->getTags() as $name => $_) {
            self::assertTrue($parser->tags->has($name));
        }

        foreach ($platform->getAliases() as $alias => $_) {
            self::assertTrue($parser->tags->has($alias));
        }
    }

    #[DataProvider('platformDataProvider')]
    public function testPlatformAloneProvidesEveryCombinatorOfItsTags(PlatformInterface $platform): void
    {
        $names = [];

        foreach ($platform->getTags() as $definition) {
            self::collectCombinatorNames($definition->spec, $names);
        }

        self::assertNotSame([], $names);

        foreach (\array_keys($names) as $name) {
            $probe = self::createProbePlatform($name);
            $parser = new DocBlockParser([$platform, $probe]);

            $tag = $parser->factory->create('probe', '');

            if ($tag instanceof InvalidTag) {
                self::assertNotInstanceOf(
                    InvalidCombinatorForDefinitionException::class,
                    $tag->reason,
                    \sprintf('Combinator <%s> is not available', $name),
                );
            }
        }
    }

    public function testLaterPlatformCombinatorOverridesBaseOne(): void
    {
        $override = new class extends Platform {
            public function getCombinators(): iterable
            {
                return [
                    'Type' => static function (Cursor $cursor): string {
                        $cursor->readRemainder();

                        return 'overridden';
                    },
                ];
            }
        };

        $parser = new DocBlockParser([
            $override,
            self::createProbePlatform('Type', 'type'),
        ]);

        $tag = $parser->factory->create('probe', 'int');

        self::assertInstanceOf(Tag::class, $tag);
        self::assertSame('overridden', $tag->description?->__toString());
    }

    /**
     * @return iterable<non-empty-string, array{PlatformInterface}>
     */
    public static function platformDataProvider(): iterable
    {
        yield 'standard' => [new StandardPlatform()];
        yield 'phpDocumentor' => [new PhpDocumentorPlatform()];
        yield 'PHPStan' => [new PhpStanPlatform()];
        yield 'Psalm' => [new PsalmPlatform()];
        yield 'Phan' => [new PhanPlatform()];
        yield 'PhpStorm' => [new PhpStormPlatform()];
        yield 'PHP CodeSniffer' => [new PhpCodeSnifferPlatform()];
    }

    /**
     * @param array<non-empty-string, true> $names
     */
    private static function collectCombinatorNames(object $rule, array &$names): void
    {
        $reflection = new \ReflectionObject($rule);

        foreach ($reflection->getProperties() as $property) {
            $value = $property->getValue($rule);

            if ($rule instanceof MatchRule && $property->getName() === 'rule') {
                \assert(\is_string($value) && $value !== '');

                $names[$value] = true;

                continue;
            }

            foreach (\is_array($value) ? $value : [$value] as $child) {
                if ($child instanceof RuleInterface) {
                    self::collectCombinatorNames($child, $names);
                }
            }
        }
    }

    /**
     * Creates a platform with the single `@probe` tag matching the given
     * combinator.
     *
     * @param non-empty-string $combinator
     * @param non-empty-string|null $alias
     */
    private static function createProbePlatform(string $combinator, ?string $alias = null): PlatformInterface
    {
        $definition = new class ($combinator, $alias) extends TagDefinition {
            /**
             * @param non-empty-string $combinator
             * @param non-empty-string|null $alias
             */
            public function __construct(string $combinator, ?string $alias)
            {
                parent::__construct('probe', Spec::rule($combinator, $alias));
            }

            public function create(string $name, TagPayload $result): Tag
            {
                $value = $result->find('type');

                if (!\is_string($value)) {
                    return new Tag($name);
                }

                return new Tag($name, Description::createIfNotEmpty($value));
            }
        };

        return new class ($definition) extends Platform {
            public function __construct(
                private readonly TagDefinitionInterface $definition,
            ) {}

            public function getTags(): iterable
            {
                return ['probe' => $this->definition];
            }
        };
    }
}
