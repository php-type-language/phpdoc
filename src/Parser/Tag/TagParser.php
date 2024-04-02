<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Tag;

use TypeLang\PHPDoc\Exception\InvalidTagNameException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\FactoryInterface;
use TypeLang\PHPDoc\Tag\Tag;

final class TagParser implements TagParserInterface
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_TAG = '\G@[a-zA-Z_\x80-\xff\\\][\w\x80-\xff\-:\\\]*';

    public function __construct(
        private readonly FactoryInterface $tags,
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
     * @throws \Throwable
     * @throws RuntimeExceptionInterface
     */
    public function parse(string $tag, DescriptionParserInterface $parser): Tag
    {
        $name = $this->getTagName($tag);
        /** @var non-empty-string $name */
        $name = \substr($name, 1);

        $content = \substr($tag, $offset = \strlen($name) + 1);
        $trimmed = \ltrim($content);

        try {
            return $this->tags->create($name, $trimmed, $parser);
        } catch (RuntimeExceptionInterface $e) {
            /** @var int<0, max> */
            $offset += \strlen($content) - \strlen($trimmed);

            throw $e->withSource($tag, $offset);
        }
    }
}
