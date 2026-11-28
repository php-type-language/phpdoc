<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NoinspectionTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​noinspection` tag suppresses the named IDE inspection for the
 * element that follows it.
 *
 * ```
 * "@​noinspection" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstorm.xml, edit the model instead
 */
final class NoinspectionTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'noinspection';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'identifier'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@noinspection` tag suppresses the named IDE inspection for the
                element that follows it.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): NoinspectionTag
    {
        /** @var non-empty-string */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new NoinspectionTag(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
    }
}
