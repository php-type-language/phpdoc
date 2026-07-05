<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

use TypeLang\PhpDoc\Parser\Grammar\Exception\UncapturedRuleException;

/**
 * The values a match captured, addressed by alias, handed to
 * {@see TagDefinitionInterface::create()}.
 *
 * ```
 * $result->get('uri');          // required, throws when absent
 * $result->find('description'); // optional, null when absent
 * $result->getAll('parameter'); // every value captured under the alias
 * ```
 */
final readonly class TagPayload
{
    public function __construct(
        /**
         * Values grouped by alias.
         *
         * @var array<string, list<mixed>>
         */
        private array $captures = [],
    ) {}

    public function has(string $alias): bool
    {
        return isset($this->captures[$alias]);
    }

    /**
     * Returns the single value captured under $alias.
     *
     * @throws UncapturedRuleException when nothing was captured under the alias
     */
    public function get(string $alias): mixed
    {
        $values = $this->captures[$alias]
            ?? throw UncapturedRuleException::becauseValueNotCaptured($alias);

        return $values[0];
    }

    /**
     * Returns the single value captured under $alias, or {@see null} when the
     * (optional) element did not match.
     */
    public function find(string $alias): mixed
    {
        return $this->captures[$alias][0] ?? null;
    }

    /**
     * Returns every value captured under $alias, in match order.
     *
     * @return list<mixed>
     */
    public function getAll(string $alias): array
    {
        return $this->captures[$alias] ?? [];
    }
}
