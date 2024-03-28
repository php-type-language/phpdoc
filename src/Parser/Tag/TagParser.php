<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Tag;

use TypeLang\PHPDoc\Exception\InvalidTagNameException;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\TagInterface;

final class TagParser implements TagParserInterface
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_TAG = '\G@[a-zA-Z_\x80-\xff\\\][\w\x80-\xff\-:\\\]*';

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
     * @throws InvalidTagNameException
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

    /**
     * @throws InvalidTagNameException
     */
    public function parse(string $tag, DescriptionParserInterface $parser = null): TagInterface
    {
        // Tag name like ["var", "example"] extracted from "@var example"
        [$name, $content] = $this->getTagParts($tag);

        if ($parser !== null) {
            $content = $parser->parse($content, $this);
        }

        return new Tag($name, $content);
    }
}
