<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

use TypeLang\PhpDoc\Parser\Grammar\Rule\AlternationRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\LiteralRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\OptionalityRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RepetitionRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\SequencingRule;

final readonly class Spec
{
    /**
     * @param non-empty-string $value
     * @param non-empty-string|null $alias
     */
    public static function literal(string $value, ?string $alias = null): LiteralRule
    {
        return new LiteralRule($value, $alias);
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $alias
     */
    public static function rule(string $name, ?string $alias = null): MatchRule
    {
        return new MatchRule($name, $alias);
    }

    public static function sequence(RuleInterface $rule, RuleInterface ...$other): SequencingRule
    {
        return new SequencingRule($rule, ...$other);
    }

    public static function oneOf(RuleInterface $rule, RuleInterface ...$other): AlternationRule
    {
        return new AlternationRule($rule, ...$other);
    }

    public static function maybe(RuleInterface $rule): OptionalityRule
    {
        return new OptionalityRule($rule);
    }

    /**
     * @param int<0, max> $times
     */
    public static function repeat(RuleInterface $rule, int $times = 0): RepetitionRule
    {
        return new RepetitionRule($rule, $times);
    }
}
