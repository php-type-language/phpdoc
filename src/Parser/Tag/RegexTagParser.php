<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Tag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\DocBlock\Tag\UnknownTag;
use TypeLang\PHPDoc\Exception\InvalidTagNameException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

final class RegexTagParser implements TagParserInterface
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_TAG = '\G@[a-zA-Z_\x80-\xff\\\][\w\x80-\xff\-:\\\]*';

    public function __construct(
        private readonly TagFactoryInterface $tags = new TagFactory(),
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
     * @throws \Throwable
     * @throws RuntimeExceptionInterface
     */
    public function parse(string $tag, DescriptionParserInterface $parser): TagInterface
    {
        try {
            $name = $this->getTagName($tag);
        } catch (InvalidTagNameException $e) {
            return new UnknownTag($e, description: $tag);
        }

        /** @var non-empty-string $name */
        $name = \substr($name, 1);

        $content = \substr($tag, $offset = \strlen($name) + 1);
        $trimmed = \ltrim($content);

        try {
            return $this->tags->create($name, $trimmed, $parser);
        } catch (RuntimeExceptionInterface $e) {
            $offset += \strlen($content) - \strlen($trimmed);

            /** @var int<0, max> $offset */
            throw $e->withSource($tag, $offset);
        }
    }
}
