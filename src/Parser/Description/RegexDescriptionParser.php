<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Description;

use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PHPDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Parser\Tag\RegexTagParser;
use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;

class RegexDescriptionParser implements DescriptionParserInterface
{
    public function __construct(
        private readonly TagParserInterface $tags = new RegexTagParser(),
    ) {}

    public function parse(string $description): DescriptionInterface
    {
        $components = [];
        $last = null;

        foreach ($this->getDocBlockChunks($description) as $chunk) {
            $component = $this->createFromChunk($chunk);

            // In case of `[N => Description, N+1 => Description]` we should
            // merge them into one.
            if ($component instanceof Description && $last instanceof Description) {
                // Remove [N] description object from the
                // end of the components list.
                $previous = \array_pop($components);

                // Merge current component from the previous one
                // and replace current component that should be added to
                // components list with the merged one.
                $component = new Description($previous . $component);
            }

            $components[] = $last = $component;
        }

        return match (\count($components)) {
            0 => new Description(),
            1 => match (true) {
                $components[0] instanceof Description => $components[0],
                default => new TaggedDescription($components),
            },
            default => new TaggedDescription($components),
        };
    }

    private function createFromChunk(string $chunk): DescriptionInterface|TagInterface
    {
        if ($chunk === '@') {
            return new Description('{@}');
        }

        if (\str_starts_with($chunk, '@')) {
            try {
                return $this->tags->parse($chunk, $this);
            } catch (\Throwable) {
                return new Description('{' . $chunk . '}');
            }
        }

        return new Description($chunk);
    }

    /**
     * @return list<non-empty-string>
     */
    private function getDocBlockChunks(string $contents): array
    {
        $result = [];
        $offset = 0;

        foreach ($this->getDocBlockTags($contents) as [$tag, $at]) {
            if ($offset !== $at) {
                $result[] = \substr($contents, $offset, $at - $offset);
            }

            $result[] = \substr($tag, 1, -1);

            $offset = $at + \strlen($tag);
        }

        if ($offset < \strlen($contents)) {
            $result[] = \substr($contents, $offset);
        }

        /** @var list<non-empty-string> */
        return \array_filter($result, static fn(string $chunk): bool => $chunk !== '');
    }

    /**
     * @return list<array{non-empty-string, int<0, max>}>
     */
    private function getDocBlockTags(string $contents): array
    {
        if (!\str_contains($contents, '{@')) {
            return [];
        }

        \preg_match_all(
            pattern: '/\{@[^}{]*+(?:(?R)[^}{]*)*+}/',
            subject: $contents,
            matches: $matches,
            flags: \PREG_OFFSET_CAPTURE,
        );

        /** @var list<array{non-empty-string, int<0, max>}> */
        return $matches[0] ?? [];
    }
}
