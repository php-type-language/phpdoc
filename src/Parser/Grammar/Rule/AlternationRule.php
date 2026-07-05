<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Matches the first of the given alternatives that applies.
 */
final readonly class AlternationRule implements ProductionInterface
{
    /**
     * @var non-empty-list<RuleInterface>
     */
    private array $rules;

    public function __construct(RuleInterface $rule, RuleInterface ...$other)
    {
        $this->rules = \array_values([$rule, ...$other]);
    }

    public function match(Context $context): void
    {
        foreach ($this->rules as $rule) {
            $snapshot = $context->mark();

            try {
                $rule->match($context);

                return;
            } catch (NoMatchException) {
                $context->rollback($snapshot);
            }
        }

        throw new NoMatchException(\sprintf('Expected one of: %s', $this));
    }

    public function __toString(): string
    {
        return \sprintf('( %s )', \implode(' | ', \array_map(\strval(...), $this->rules)));
    }
}
