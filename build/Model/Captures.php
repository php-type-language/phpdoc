<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

use TypeLang\PhpDoc\Parser\Grammar\Rule\AlternationRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\LiteralRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\OptionalityRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RepetitionRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\SequencingRule;

/**
 * The values a grammar captures and how they are read back from the payload:
 * a required one by `get()`, an optional one by `find()`, a repeated one by
 * `getAll()`.
 */
final class Captures
{
    private function __construct(
        /**
         * Capture names in the order of their first appearance.
         *
         * @var list<non-empty-string>
         */
        public readonly array $names,
        /**
         * Names captured on every successful match.
         *
         * @var list<non-empty-string>
         */
        public readonly array $required,
        /**
         * Names captured inside a repetition.
         *
         * @var list<non-empty-string>
         */
        public readonly array $repeated,
        /**
         * Names of the captured literals.
         *
         * @var list<non-empty-string>
         */
        public readonly array $literals,
        /**
         * Names of the combinators referenced by the grammar.
         *
         * @var list<non-empty-string>
         */
        public readonly array $combinators,
    ) {}

    public static function collect(RuleInterface $rule): self
    {
        $names = $repeated = $literals = $combinators = [];

        self::walk($rule, false, $names, $repeated, $literals, $combinators);

        return new self(
            names: \array_values(\array_unique($names)),
            required: \array_values(\array_unique(self::getRequired($rule))),
            repeated: \array_values(\array_unique($repeated)),
            literals: \array_values(\array_unique($literals)),
            combinators: \array_values(\array_unique($combinators)),
        );
    }

    /**
     * @param non-empty-string $name
     */
    public function isRequired(string $name): bool
    {
        return \in_array($name, $this->required, true);
    }

    /**
     * @param non-empty-string $name
     */
    public function isRepeated(string $name): bool
    {
        return \in_array($name, $this->repeated, true);
    }

    /**
     * @param non-empty-string $name
     */
    public function isLiteral(string $name): bool
    {
        return \in_array($name, $this->literals, true);
    }

    /**
     * @param list<non-empty-string> $names
     * @param list<non-empty-string> $repeated
     * @param list<non-empty-string> $literals
     * @param list<non-empty-string> $combinators
     */
    private static function walk(
        RuleInterface $rule,
        bool $isRepeated,
        array &$names,
        array &$repeated,
        array &$literals,
        array &$combinators,
    ): void {
        if ($rule instanceof MatchRule) {
            $combinators[] = $rule->rule;
        }

        if (($rule instanceof MatchRule || $rule instanceof LiteralRule) && $rule->alias !== null) {
            $names[] = $rule->alias;

            if ($isRepeated) {
                $repeated[] = $rule->alias;
            }

            if ($rule instanceof LiteralRule) {
                $literals[] = $rule->alias;
            }
        }

        foreach (self::getChildren($rule) as $child) {
            self::walk(
                rule: $child,
                isRepeated: $isRepeated || $rule instanceof RepetitionRule,
                names: $names,
                repeated: $repeated,
                literals: $literals,
                combinators: $combinators,
            );
        }
    }

    /**
     * Names captured on every successful match of the rule.
     *
     * @return list<non-empty-string>
     */
    private static function getRequired(RuleInterface $rule): array
    {
        if ($rule instanceof MatchRule || $rule instanceof LiteralRule) {
            if ($rule->alias === null) {
                return [];
            }

            return [$rule->alias];
        }

        if ($rule instanceof SequencingRule) {
            return \array_merge(...\array_map(self::getRequired(...), $rule->rules));
        }

        if ($rule instanceof AlternationRule) {
            $alternatives = \array_map(self::getRequired(...), $rule->rules);

            /** @var list<non-empty-string> */
            return \array_values(\array_intersect(...$alternatives));
        }

        if ($rule instanceof RepetitionRule && $rule->min > 0) {
            return self::getRequired($rule->rule);
        }

        return [];
    }

    /**
     * @return list<RuleInterface>
     */
    public static function getChildren(RuleInterface $rule): array
    {
        return match (true) {
            $rule instanceof SequencingRule, $rule instanceof AlternationRule => $rule->rules,
            $rule instanceof OptionalityRule, $rule instanceof RepetitionRule => [$rule->rule],
            default => [],
        };
    }
}
