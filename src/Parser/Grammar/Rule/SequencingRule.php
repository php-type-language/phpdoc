<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Matches an ordered sequence of rules, one after another.
 */
final readonly class SequencingRule implements ProductionInterface
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
        $snapshot = $context->mark();

        try {
            foreach ($this->rules as $rule) {
                $context->cursor->skipWhitespace();
                $rule->match($context);
            }
        } catch (NoMatchException $e) {
            $context->rollback($snapshot);

            throw $e;
        }
    }

    public function __toString(): string
    {
        return \implode(' ', \array_map(\strval(...), $this->rules));
    }
}
