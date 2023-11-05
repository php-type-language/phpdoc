<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Extractor;

use TypeLang\PhpDocParser\Exception\TagWithoutNameException;

final class TagNameExtractor
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
     * @psalm-immutable
     * @return list{non-empty-string, non-empty-string|null}
     * @throws \Throwable
     */
    public function extract(string $body): array
    {
        if ($body === '') {
            throw TagWithoutNameException::fromEmptyBody();
        }

        if ($body[0] !== '@') {
            throw TagWithoutNameException::fromNonTaggedBody();
        }

        $foundBreakChar = \strpbrk($body, " \t\n\r\0\x0B");

        if ($foundBreakChar === false) {
            return $this->createFromBodiless($body);
        }

        /** @var int<0, max> $foundBreakOffset */
        $foundBreakOffset = \strpos($body, $foundBreakChar);

        return $this->createFromNamed($body, $foundBreakOffset);
    }

    /**
     * @psalm-immutable
     * @return list{non-empty-string, non-empty-string|null}
     * @throws \Throwable
     */
    private function createFromNamed(string $body, int $offset): array
    {
        $name = \substr($body, 1, $offset - 1);

        if ($name === '') {
            throw TagWithoutNameException::fromTagWithoutName();
        }

        $body = \ltrim(\substr($body, $offset));

        if ($body === '') {
            return [$name, null];
        }

        return [$name, $body];
    }

    /**
     * @psalm-immutable
     * @param non-empty-string $tag
     * @return list{non-empty-string, null}
     * @throws \Throwable
     */
    private function createFromBodiless(string $body): array
    {
        if (\strlen($body) === 1) {
            throw TagWithoutNameException::fromTagWithoutName();
        }

        /** @var list{non-empty-string, null} */
        return [\substr($body, 1), null];
    }
}
