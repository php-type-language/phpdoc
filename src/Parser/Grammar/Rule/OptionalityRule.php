<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Matches an inner rule when it applies, and is skipped when it does not.
 */
final readonly class OptionalityRule implements ProductionInterface
{
    public function __construct(
        private RuleInterface $rule,
    ) {}

    public function match(Context $context): void
    {
        $snapshot = $context->mark();

        try {
            $this->rule->match($context);
        } catch (NoMatchException) {
            $context->rollback($snapshot);
        }
    }

    public function __toString(): string
    {
        return \sprintf('[ %s ]', $this->rule);
    }
}
