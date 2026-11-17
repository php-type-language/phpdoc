<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SelfOutTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@self-out` tag documents the refined type of `$this` after a method
 * call, letting callers see a narrower object type than the one they
 * started with.
 *
 * ```
 * "@self-out" <Type> [ <Description> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class SelfOutTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'self-out';
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
        );
    }

    public function create(string $name, TagPayload $result): SelfOutTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new SelfOutTag(
            name: $name,
            statement: $statement,
            description: $description,
        );
    }
}
