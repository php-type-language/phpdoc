<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Matches an inner rule as many times as it applies.
 */
final readonly class RepetitionRule implements ProductionInterface
{
    public function __construct(
        private RuleInterface $rule,
        /**
         * Least number of repetitions required to match.
         *
         * @var int<0, max>
         */
        private int $min = 0,
    ) {}

    public function match(Context $context): void
    {
        $count = 0;

        while (true) {
            $snapshot = $context->mark();

            try {
                if ($count > 0) {
                    $context->cursor->skipWhitespace();
                }

                $this->rule->match($context);
            } catch (NoMatchException) {
                $context->rollback($snapshot);

                break;
            }

            ++$count;

            if ($context->cursor->position === $snapshot[0]) {
                break;
            }
        }

        if ($count < $this->min) {
            throw new NoMatchException(\sprintf(
                'Expected at least %d occurrence(s) of %s, got %d',
                $this->min,
                $this->rule,
                $count,
            ));
        }
    }

    public function __toString(): string
    {
        return $this->min > 0
            ? \sprintf('%s ...', $this->rule)
            : \sprintf('[ %s ... ]', $this->rule);
    }
}
