<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PhpDoc\Build\Model\Example;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * Every example of the model is read by the default parser without an error
 * and uses the tag it illustrates.
 */
final class ExamplesTest extends TestCase
{
    /**
     * @param non-empty-string $name
     */
    #[DataProvider('exampleDataProvider')]
    public function testExampleIsParsed(string $name, Example $example): void
    {
        $parser = DocBlockParser::create();
        $found = false;
        $docblocks = self::getDocBlocks($example->code);

        self::assertNotSame([], $docblocks, 'The example contains no docblock');

        foreach ($docblocks as $docblock) {
            $block = $parser->parse($docblock);

            foreach ([...self::getInlineTags($block->description), ...$block->tags] as $tag) {
                foreach ([$tag, ...self::getInlineTags($tag->description)] as $nested) {
                    if ($nested instanceof InvalidTag) {
                        self::fail(\sprintf('@%s is invalid: %s', $nested->name, $nested->reason->getMessage()));
                    }

                    $found = $found || self::isNamed($nested, $name);
                }
            }
        }

        self::assertTrue($found, \sprintf('The example does not use @%s', $name));
    }

    /**
     * Tags written inline in the description (`{@see ...}`).
     *
     * @return list<TagInterface>
     */
    private static function getInlineTags(?DescriptionInterface $description): array
    {
        if (!$description instanceof TaggedDescription) {
            return [];
        }

        return \array_values($description->tags);
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string, Example}>
     */
    public static function exampleDataProvider(): iterable
    {
        $model = ModelTest::getModel();

        foreach ($model->tags as $tag) {
            foreach ($tag->examples as $index => $example) {
                yield \sprintf('@%s #%d', $tag->name, $index + 1) => [$tag->name, $example];
            }
        }

        foreach ($model->model->aliases as $alias) {
            foreach ($alias->examples as $index => $example) {
                yield \sprintf('@%s #%d', $alias->name, $index + 1) => [$alias->name, $example];
            }
        }
    }

    /**
     * @return list<string>
     */
    private static function getDocBlocks(string $code): array
    {
        $result = [];

        foreach (\token_get_all("<?php\n" . $code) as $token) {
            if (\is_array($token) && $token[0] === \T_DOC_COMMENT) {
                $result[] = $token[1];
            }
        }

        return $result;
    }

    private static function isNamed(TagInterface $tag, string $name): bool
    {
        return \strtolower($tag->name) === \strtolower($name);
    }
}
