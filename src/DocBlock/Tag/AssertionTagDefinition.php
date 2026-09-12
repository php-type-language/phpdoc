<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Combinator\AssertSubjectCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The definition every assertion tag is read by.
 *
 * The family shares its whole body, the tags of it differing only by the name
 * they are written under and by the moment the assertion holds at:
 *
 * ```
 * "@assert"          [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * "@assert-if-true"  [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * "@assert-if-false" [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * ```
 */
abstract class AssertionTagDefinition extends TagDefinition
{
    /**
     * The names the prefix of the type is reported under, one per prefix.
     *
     * @var non-empty-string
     */
    private const OPERATOR_IS_NOT = 'is-not';

    /**
     * @var non-empty-string
     */
    private const OPERATOR_EQUALS = 'equals';

    /**
     * @var non-empty-string
     */
    private const OPERATOR_NOT_EQUALS = 'not-equals';

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            name: $name,
            spec: Spec::sequence(
                // A literal reports nothing but its own presence, so each of
                // the prefixes is asked about under a name of its own. The
                // longest one is offered first, so that the "!" of a "!=" is
                // not taken for the whole of it.
                Spec::maybe(
                    Spec::oneOf(
                        Spec::literal(AssertOperator::NotEquals->value, self::OPERATOR_NOT_EQUALS),
                        Spec::literal(AssertOperator::IsNot->value, self::OPERATOR_IS_NOT),
                        Spec::literal(AssertOperator::Equals->value, self::OPERATOR_EQUALS),
                    ),
                ),
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::rule(AssertSubjectCombinator::NAME, 'subject'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    final public function create(string $name, TagPayload $result): AssertionTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var CodeReference $subject */
        $subject = $result->get('subject');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return $this->createAssertion(
            name: $name,
            statement: $type,
            subject: $subject,
            operator: match (true) {
                $result->find(self::OPERATOR_NOT_EQUALS) !== null => AssertOperator::NotEquals,
                $result->find(self::OPERATOR_IS_NOT) !== null => AssertOperator::IsNot,
                $result->find(self::OPERATOR_EQUALS) !== null => AssertOperator::Equals,
                default => AssertOperator::Is,
            },
            description: $description,
        );
    }

    /**
     * @param non-empty-string $name
     */
    abstract protected function createAssertion(
        string $name,
        TypeReference $statement,
        CodeReference $subject,
        AssertOperator $operator,
        ?DescriptionInterface $description,
    ): AssertionTag;
}
