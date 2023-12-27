<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Description;

use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlock\TagFactoryInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagNameException;

abstract class DescriptionFactory implements DescriptionFactoryInterface
{
    /**
     * @param TagFactoryInterface<TagInterface> $tags
     */
    public function __construct(
        public readonly TagFactoryInterface $tags,
    ) {}

    public function create(string $contents): Description
    {
        if ($contents === '') {
            return new Description();
        }

        $tags = [];
        $tagIdentifier = 0;
        $description = '';

        foreach ($this->getDocBlockChunks($contents) as $chunk) {
            if ($chunk === '@') {
                $description .= '{@}';
                continue;
            }

            if (\str_starts_with($chunk, '@')) {
                try {
                    $tags[] = $this->tags->create($chunk);
                    $description .= $this->createDescriptionChunkPlaceholder(++$tagIdentifier);
                } catch (InvalidTagNameException) {
                    $description .= "{{$chunk}}";
                }

                continue;
            }

            $description .= $this->escapeDescriptionChunk($chunk);
        }

        return new Description($description, $tags);
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
