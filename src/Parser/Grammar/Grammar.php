<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar;

use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;

/**
 * The named terminals ({@see MatchRule}) that tag definitions may reference.
 *
 * A reader reads its value from the {@see Cursor} and returns it, or throws a
 * {@see NoMatchException} when the input does not fit.
 *
 * ```
 * $grammar->add('URI', static function (Cursor $cursor): string {
 *     $uri = $cursor->readWord();
 *
 *     if ($uri === '') {
 *         throw new NoMatchException('Expected a URI');
 *     }
 *
 *     return $uri;
 * });
 * ```
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 *
 * @template-implements \IteratorAggregate<non-empty-string, CombinatorType>
 */
final class Grammar implements \Countable, \IteratorAggregate
{
    /**
     * @var array<non-empty-string, CombinatorType>
     */
    private array $combinators = [];

    /**
     * @param iterable<non-empty-string, CombinatorType> $rules
     */
    public function __construct(iterable $rules = [])
    {
        $this->combinators = \iterator_to_array($rules);
    }

    /**
     * Registers (or overrides) a named terminal reader.
     *
     * @param non-empty-string $name
     * @param CombinatorType $reader
     */
    public function add(string $name, callable $reader): void
    {
        $this->combinators[$name] = $reader;
    }

    /**
     * @param non-empty-string $name
     */
    public function has(string $name): bool
    {
        return isset($this->combinators[$name]);
    }

    /**
     * @param non-empty-string $name
     * @return CombinatorType
     * @throws InvalidCombinatorException
     */
    public function get(string $name): callable
    {
        return $this->combinators[$name]
            ?? throw InvalidCombinatorException::becauseInvalidRule($name);
    }

    public function getIterator(): \Traversable
    {
        /** @var \ArrayIterator<non-empty-string, CombinatorType> */
        return new \ArrayIterator($this->combinators);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->combinators);
    }
}
