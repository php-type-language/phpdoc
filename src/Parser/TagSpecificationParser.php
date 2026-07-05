<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorForDefinitionException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;

/**
 * @phpstan-import-type CombinatorType from Grammar
 */
final readonly class TagSpecificationParser
{
    private Grammar $grammar;

    /**
     * @param iterable<non-empty-string, CombinatorType> $combinators
     */
    public function __construct(iterable $combinators)
    {
        $this->grammar = new Grammar($combinators);
    }

    public function parse(TagDefinitionInterface $definition, string $name, string $suffix): TagPayload
    {
        $cursor = new Cursor($suffix);
        $context = new Context($cursor, $this->grammar);
        $rule = $definition->spec;

        try {
            $rule->match($context);
        } catch (InvalidCombinatorException $e) {
            throw InvalidCombinatorForDefinitionException::becauseInvalidRuleForDefinition(
                name: $e->name,
                definition: $definition,
                previous: $e,
            );
        } catch (NoMatchException) {
            throw MalformedTagException::becauseTagBodyIsMalformed(
                tag: $name,
                grammar: (string) $rule,
                source: $suffix,
                offset: $cursor->furthestOffset,
            );
        }

        return $context->toMatchedResult();
    }
}
