<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Snapshot;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;
use TypeLang\PhpDoc\Parser\Grammar\Rule\AlternationRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\LiteralRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\OptionalityRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RepetitionRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\SequencingRule;
use TypeLang\PhpDoc\Platform\PlatformInterface;

/**
 * Renders the tags and combinators a parser is built from as a stable text.
 *
 * The text is a snapshot of the runtime contract of the platforms: tag names,
 * aliases, definition and tag classes, placements, grammars with capture names
 * and the combinators available to them. A runtime alias is rendered as
 * `alias = target`, without repeating the definition.
 */
final class RegistryDumper
{
    /**
     * Renders every tag of the parser with its full description.
     *
     * @throws \ReflectionException in case of the parser internals are changed
     */
    public function dump(DocBlockParser $parser): string
    {
        $lines = ['## combinators', ...$this->dumpCombinators($parser), '', '## tags'];

        foreach ($this->groupByDefinition($parser) as $key => [$canonical, $definition]) {
            if ($canonical !== $key) {
                $lines[] = \sprintf('@%s = @%s', $key, $canonical);

                continue;
            }

            $lines[] = '@' . $key;
            $lines[] = '  definition: ' . $definition::class;
            $lines[] = '  name: ' . $definition->name;
            $lines[] = '  creates: ' . $this->getCreatedClass($definition);
            $lines[] = '  placement: ' . $definition->placement->name;
            $lines[] = '  grammar: ' . $this->dumpRule($definition->spec);
        }

        return \implode("\n", $lines) . "\n";
    }

    /**
     * Renders only the tag names and definition classes of the parser, the
     * details of each tag are expected in the snapshot of its own platform.
     */
    public function dumpSummary(DocBlockParser $parser): string
    {
        $lines = [];

        foreach ($this->groupByDefinition($parser) as $key => [$canonical, $definition]) {
            if ($canonical !== $key) {
                $lines[] = \sprintf('@%s = @%s', $key, $canonical);

                continue;
            }

            $lines[] = \sprintf('@%s: %s', $key, $definition::class);
        }

        return \implode("\n", $lines) . "\n";
    }

    /**
     * Renders the parser built from the single platform.
     *
     * @throws \ReflectionException in case of the parser internals are changed
     */
    public function dumpPlatform(PlatformInterface $platform): string
    {
        return $this->dump(new DocBlockParser([$platform]));
    }

    /**
     * Maps each registered name to the name the definition instance is
     * registered for, a runtime alias maps to its target.
     *
     * The target is the name equal to the canonical name of the definition,
     * or the first name sharing the instance (in registry order) when there is
     * no such name.
     *
     * @return array<non-empty-string, array{non-empty-string, TagDefinitionInterface}>
     */
    private function groupByDefinition(DocBlockParser $parser): array
    {
        $targets = [];

        foreach ($parser->tags as $key => $definition) {
            $id = \spl_object_id($definition);

            if (!isset($targets[$id]) || $key === $definition->name) {
                $targets[$id] = $key;
            }
        }

        $result = [];

        foreach ($parser->tags as $key => $definition) {
            $result[$key] = [$targets[\spl_object_id($definition)], $definition];
        }

        \ksort($result);

        return $result;
    }

    /**
     * @return list<non-empty-string>
     * @throws \ReflectionException in case of the parser internals are changed
     */
    private function dumpCombinators(DocBlockParser $parser): array
    {
        $result = [];

        foreach ($this->getGrammar($parser) as $name => $combinator) {
            $class = \is_object($combinator) ? $combinator::class : \get_debug_type($combinator);

            $result[] = \sprintf('%s: %s', $name, $class);
        }

        \sort($result);

        return $result;
    }

    /**
     * The grammar is not a part of the public API of the parser, it is read
     * through the factory internals.
     *
     * @throws \ReflectionException in case of the parser internals are changed
     */
    private function getGrammar(DocBlockParser $parser): Grammar
    {
        $specificationParser = (new \ReflectionProperty($parser->factory, 'parser'))
            ->getValue($parser->factory);

        \assert(\is_object($specificationParser));

        $grammar = (new \ReflectionProperty($specificationParser, 'grammar'))
            ->getValue($specificationParser);

        \assert($grammar instanceof Grammar);

        return $grammar;
    }

    /**
     * Declared return type of {@see TagDefinitionInterface::create()}.
     *
     * A definition family declares a common base tag there and delegates to an
     * abstract method of the concrete definition, the type of that method is
     * taken in this case.
     *
     * @throws \ReflectionException in case of the created class is not loaded
     */
    private function getCreatedClass(TagDefinitionInterface $definition): string
    {
        $reflection = new \ReflectionObject($definition);
        $created = (string) $reflection->getMethod('create')->getReturnType();

        if (!\class_exists($created) || (new \ReflectionClass($created))->isFinal()) {
            return $created;
        }

        foreach ($reflection->getMethods() as $method) {
            if ($method->getDeclaringClass()->name !== $reflection->name) {
                continue;
            }

            $type = (string) $method->getReturnType();

            if (\is_subclass_of($type, $created)) {
                return $type;
            }
        }

        return $created;
    }

    private function dumpRule(RuleInterface $rule): string
    {
        return match (true) {
            $rule instanceof MatchRule => $this->dumpMatch($rule),
            $rule instanceof LiteralRule => $this->dumpLiteral($rule),
            $rule instanceof SequencingRule => \sprintf('seq(%s)', $this->dumpRules($rule->rules)),
            $rule instanceof AlternationRule => \sprintf('oneOf(%s)', $this->dumpRules($rule->rules)),
            $rule instanceof OptionalityRule => \sprintf('maybe(%s)', $this->dumpRule($rule->rule)),
            $rule instanceof RepetitionRule => \sprintf('repeat(%s, %d)', $this->dumpRule($rule->rule), $rule->min),
            default => \sprintf('%s(%s)', $rule::class, $rule),
        };
    }

    /**
     * @param non-empty-list<RuleInterface> $rules
     */
    private function dumpRules(array $rules): string
    {
        return \implode(', ', \array_map($this->dumpRule(...), $rules));
    }

    private function dumpMatch(MatchRule $rule): string
    {
        $result = \sprintf('<%s>', $rule->rule);

        if ($rule->renderAs !== null) {
            $result .= \sprintf(' shown as <%s>', $rule->renderAs);
        }

        if ($rule->alias !== null) {
            $result .= ' as ' . $rule->alias;
        }

        return $result;
    }

    private function dumpLiteral(LiteralRule $rule): string
    {
        $result = \sprintf('"%s"', $rule->value);

        if ($rule->alias !== null) {
            $result .= ' as ' . $rule->alias;
        }

        return $result;
    }
}
