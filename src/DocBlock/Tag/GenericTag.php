<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * This tag is created if the docblock is not supported by parser but looks
 * like a correct docblock.
 */
final class GenericTag extends Tag implements InvalidTagInterface
{
    private ?\Throwable $reason = null;

    public function getReason(): \Throwable
    {
        return $this->reason ??= new \OutOfRangeException(
            message: \sprintf('Tag "@%s" is not supported', $this->getName()),
        );
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     error: non-empty-string,
     *     description?: array{
     *         template: string,
     *         tags: list<array>
     *     }
     * }
     */
    public function toArray(): array
    {
        $reason = $this->getReason();

        return [
            ...parent::toArray(),
            'error' => $reason->getMessage(),
        ];
    }
}
