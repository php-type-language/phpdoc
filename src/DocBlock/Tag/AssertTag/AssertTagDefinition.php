<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\AssertionTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertOperator;

/**
 * The `@assert` tag asserts that the given subject is narrowed to the type it
 * carries once the call returns.
 *
 * ```
 * "@assert" [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * ```
 */
final class AssertTagDefinition extends AssertionTagDefinition
{
    public const NAME = 'assert';

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
    ): AssertTag {
        return new AssertTag($name, $statement, $subject, $operator, $description);
    }
}
