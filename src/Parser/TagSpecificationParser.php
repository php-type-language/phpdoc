<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\InvalidCombinatorForDefinitionException;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\Parser
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final class TagSpecificationParser
{
    private readonly Grammar $grammar;

    /**
     * @param iterable<non-empty-string, CombinatorType> $combinators
     */
    public function __construct(iterable $combinators)
    {
        $this->grammar = new Grammar($combinators);
    }

    /**
     * @throws InvalidCombinatorForDefinitionException in case of the spec of
     *        the definition refers to an unknown combinator
     * @throws MalformedTagException in case of the tag body does not match
     *        the spec of the definition
     * @throws ParsingExceptionInterface in case of a combinator fails on
     *        a malformed input
     */
    public function parse(TagDefinitionInterface $definition, string $name, string $suffix): TagPayload
    {
        $cursor = new Cursor($suffix);
        $context = new Context($cursor, $this->grammar);
        $rule = $definition->spec;

        try {
            $rule->match($context);

            $cursor->skipWhitespace();

            if (!$cursor->isEof()) {
                throw new NoMatchException();
            }
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
                offset: $cursor->getFurthestOffset(),
            );
        }

        return $context->toMatchedResult();
    }
}
