<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ExpectedExceptionTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@expectedException` tag declares the `Throwable` a test method is
 * expected to throw.
 *
 * ```
 * "@expectedException" <Type> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstorm.xml, edit the model instead
 */
final class ExpectedExceptionTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'expectedException';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@expectedException` tag declares the `Throwable` a test method is
                expected to throw.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ExpectedExceptionTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ExpectedExceptionTag(
            name: $name,
            statement: $statement,
            description: $description,
        );
    }
}
