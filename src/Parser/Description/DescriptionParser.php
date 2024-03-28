<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Description;

use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;
use TypeLang\PHPDoc\Tag\Description;

abstract class DescriptionParser implements DescriptionParserInterface
{
    public function parse(string $description, TagParserInterface $parser = null): Description
    {
        if ($parser === null || $description === '') {
            return new Description($description);
        }

        return $this->doParseDescription($description, $parser);
    }

    private function doParseDescription(string $description, TagParserInterface $parser): Description
    {
        $tags = [];
        $tagIdentifier = 0;
        $result = '';

        foreach ($this->getDocBlockChunks($description) as $chunk) {
            if ($chunk === '@') {
                $result .= '{@}';
                continue;
            }

            if (\str_starts_with($chunk, '@')) {
                try {
                    $tags[] = $parser->parse($chunk, $this);
                    $result .= $this->createDescriptionChunkPlaceholder(++$tagIdentifier);
                } catch (\Throwable) {
                    $result .= "{{$chunk}}";
                }

                continue;
            }

            $result .= $this->escapeDescriptionChunk($chunk);
        }

        return new Description(\trim($result), $tags);
    }

    /**
     * @param int<0, max> $tagId
     *
     * @return non-empty-string
     */
    abstract protected function createDescriptionChunkPlaceholder(int $tagId): string;

    /**
     * @return ($chunk is non-empty-string ? non-empty-string : string)
     */
    abstract protected function escapeDescriptionChunk(string $chunk): string;

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
        return \array_filter($result);
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
