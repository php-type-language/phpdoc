<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;

/**
 * Represents a parsed DocBlock comment.
 *
 * A structured view over the two logical parts of any documentation block:
 * An instance of description and an ordered set of ta
 *
 * The object is a read-only, IMMUTABLE value. It is a snapshot of a comment
 * and never mutates. It additionally behaves as a collection of its tags, so
 * it can be counted, iterated and accessed by offset.
 *
 * ```
 * // Iterate over every tag in the order they appear in the comment
 * foreach ($block as $tag) {
 *     echo $tag->name; // "param", "param", "return", "throws"
 * }
 *
 * // Count the number of tags
 * echo \count($block); // 4
 *
 * // Access a tag by its positional offset
 * $first = $block[0];
 * ```
 *
 * @template-implements \ArrayAccess<array-key, TagInterface>
 * @template-implements \IteratorAggregate<array-key, TagInterface>
 */
final readonly class DocBlock implements
    ComponentInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    /**
     * The ordered list of every tag contained in the comment.
     *
     * Tags are yielded in the exact order they occur in the source comment,
     * including duplicates: a comment with several `@param` lines produces
     * several {@see TagInterface} entries. When the comment has no tags, the
     * value is empty.
     *
     * ```
     * foreach ($block->tags as $tag) {
     *     printf('@%s => %s', $tag->name, $tag->description);
     * }
     * ```
     *
     * Iterating the object directly is equivalent to iterating over
     * this property.
     *
     * @var list<TagInterface>
     */
    public array $tags;

    /**
     * @param iterable<mixed, TagInterface> $tags list of all tags contained
     *        in a docblock object
     */
    public function __construct(
        /**
         * Gets an instance of the description preceding the tags.
         *
         * The description corresponds to everything before the **first tag**. For
         * the comment:
         *
         * ```
         * /**
         *  * Sends a notification to the given recipient.
         *  *
         *  * @​param string $to
         *  *​/
         * ```
         *
         * The value is a {@see DescriptionInterface} that stringifies to
         * `"Sends a notification to the given recipient."`:
         *
         * ```
         * echo $block->description; // "Sends a notification..."
         * ```
         */
        public ?DescriptionInterface $description = null,
        iterable $tags = [],
    ) {
        $this->tags = \iterator_to_array($tags, false);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->tags[$offset]);
    }

    public function offsetGet(mixed $offset): ?TagInterface
    {
        return $this->tags[$offset] ?? null;
    }

    /**
     * Writing tags through the array access syntax is **NOT SUPPORTED**.
     *
     * @internal DocBlock tags are immutable
     *
     * @throws \BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * Removing tags through the array access syntax is **NOT SUPPORTED**.
     *
     * @internal DocBlock tags are immutable
     *
     * @throws \BadMethodCallException
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->tags);
    }

    /**
     * Returns the number of tags contained in the comment.
     *
     * The value is always non-negative and equals the number of entries
     * exposed by {@see DocBlockInterface::$tags}.
     *
     * ```
     * $count = \count($block); // e.g. 4
     * ```
     *
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->tags);
    }

    public function __toString(): string
    {
        $result = [];

        if ($this->description !== null) {
            $result[] = (string) $this->description;
        }

        foreach ($this->tags as $tag) {
            $result[] = (string) $tag;
        }

        return \implode("\n", $result);
    }
}
