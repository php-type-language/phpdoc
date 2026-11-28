<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\AssertionTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertOperator;

/**
 * The `@assert-if-true` tag asserts the given type for a subject, but
 * only when the function returns `true`.
 *
 * ```
 * "@assert-if-true" [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * ```
 */
final class AssertIfTrueTagDefinition extends AssertionTagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'assert-if-true';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            description: <<<'TEXT'
                The `@assert-if-true` tag asserts the given type for a subject, but
                only when the function returns `true`.
                TEXT,
        );
    }

    protected function createAssertion(
        string $name,
        TypeReference $statement,
        CodeReference $subject,
        AssertOperator $operator,
        ?DescriptionInterface $description,
    ): AssertIfTrueTag {
        return new AssertIfTrueTag($name, $statement, $subject, $operator, $description);
    }
}
