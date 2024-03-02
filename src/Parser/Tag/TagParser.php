<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Tag;

use TypeLang\PHPDoc\Exception\InvalidTagNameException;
use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;
use TypeLang\PHPDoc\Tag\Definition\ParsableInterface;
use TypeLang\PHPDoc\Tag\Definition\UnknownDefinition;
use TypeLang\PHPDoc\Tag\RepositoryInterface;
use TypeLang\PHPDoc\Tag\TagInterface;
use TypeLang\PHPDoc\Tag\UnknownTag;

final class TagParser implements TagParserInterface
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_TAG = '\G@[a-zA-Z_\x80-\xff][\w\x80-\xff\-:]*';

    public function __construct(
        private readonly RepositoryInterface $tags,
        private readonly DefinitionInterface $unknown = new UnknownDefinition(),
    ) {}

    /**
     * Read tag name from passed content.
     *
     * Expected argument should be looks like:
     *   - "@tag"
     *   - "@tag with description"
     *   - "@tag With\TypeName"
     *   - "@tag With\TypeName And description"
     *   - "@tag With\TypeName $andVariableName"
     *   - "@tag With\TypeName $andVariableName And description"
     *   - etc...
     *
     * @phpstan-pure
     * @psalm-pure
     *
     * @throws InvalidTagNameException
     */
    private function getTagName(string $content): string
    {
        if ($content === '') {
            throw InvalidTagNameException::fromEmptyTag();
        }

        if (isset($content[0]) && $content[0] !== '@') {
            throw InvalidTagNameException::fromInvalidTagPrefix($content);
        }

        $pattern = \addcslashes(self::PATTERN_TAG, '/');

        \preg_match("/^$pattern/isum", $content, $matches);

        if (($matches[0] ?? null) === null) {
            throw InvalidTagNameException::fromEmptyTagName($content);
        }

        return $matches[0];
    }

    /**
     * @return array{non-empty-string, string}
     */
    private function getTagParts(string $content): array
    {
        $name = $this->getTagName($content);
        /** @var non-empty-string $name */
        $name = \substr($name, 1);

        $content = \substr($content, \strlen($name) + 1);
        $content = \ltrim($content);

        return [$name, $content];
    }

    public function parse(string $tag): TagInterface
    {
        // Tag name like ["var", "example"] extracted from "@var example"
        [$name, $content] = $this->getTagParts($tag);

        $definition = $this->tags->findByName($name);

        if ($definition === null) {
            return new UnknownTag(
                definition: $this->unknown,
                name: $name,
                description: $content,
            );
        }

        return $this->getTagFromDefinition($definition, $name, $content);
    }

    private function getTagFromDefinition(DefinitionInterface $definition, string $name, string $content): TagInterface
    {
        if ($definition instanceof ParsableInterface) {
            return $definition->parse($name, $content);
        }

        dd('ToDO');
    }
}
