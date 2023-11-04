<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

final class TagPartsExtractor
{
    /**
     * Extracts all components for a tag: Name + Description (with
     * optional type and name).
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
     * @return list{non-empty-string, non-empty-string|null}
     *
     * @throws \Throwable
     */
    public function extract(string $tag): array
    {
        if ($tag === '' || $tag[0] !== '@') {
            throw new \InvalidArgumentException('Could not extract tag parts from empty tag');
        }

        $foundBreakChar = \strpbrk($tag, " \t\n\r\0\x0B");

        if ($foundBreakChar === false) {
            return $this->createFromBodiless($tag);
        }

        /** @var int<0, max> $foundBreakOffset */
        $foundBreakOffset = \strpos($tag, $foundBreakChar);

        return $this->createFromNamed($tag, $foundBreakOffset);
    }

    /**
     * @return list{non-empty-string, non-empty-string|null}
     *
     * @throws \Throwable
     */
    private function createFromNamed(string $tag, int $offset): array
    {
        $name = \substr($tag, 1, $offset - 1);

        if ($name === '') {
            throw $this->emptyNameError();
        }

        $body = \ltrim(\substr($tag, $offset));

        if ($body === '') {
            return [$name, null];
        }

        return [$name, $body];
    }

    /**
     * @param non-empty-string $tag
     *
     * @return list{non-empty-string, null}
     *
     * @throws \Throwable
     */
    private function createFromBodiless(string $tag): array
    {
        if (\strlen($tag) === 1) {
            throw $this->emptyNameError();
        }

        return [\substr($tag, 1), null];
    }

    private function emptyNameError(): \Throwable
    {
        return new \InvalidArgumentException('Could not extract tag name from tag without name');
    }
}
