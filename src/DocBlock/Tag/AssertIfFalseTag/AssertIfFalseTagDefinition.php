<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\AssertionTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertOperator;

/**
 * The `@assert-if-false` tag asserts the given type for a subject, but
 * only when the function returns `false`.
 *
 * ```
 * "@assert-if-false" [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * ```
 */
final class AssertIfFalseTagDefinition extends AssertionTagDefinition
{
    public const NAME = 'assert-if-false';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function createAssertion(
        string $name,
        TypeReference $statement,
        CodeReference $subject,
        AssertOperator $operator,
        ?DescriptionInterface $description,
    ): AssertIfFalseTag {
        return new AssertIfFalseTag($name, $statement, $subject, $operator, $description);
    }
}
