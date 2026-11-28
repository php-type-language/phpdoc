<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanRealReturnTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-real-return` tag documents the actual native return type of a
 * function or method, kept distinct from the documented `@return` type.
 *
 * ```
 * "@phan-real-return" <Type> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanRealReturnTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phan-real-return';
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
                The `@phan-real-return` tag documents the actual native return type of a
                function or method, kept distinct from the documented `@return` type.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PhanRealReturnTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhanRealReturnTag(
            name: $name,
            statement: $statement,
            description: $description,
        );
    }
}
