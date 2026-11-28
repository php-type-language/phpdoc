<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AccessTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VisibilityCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Visibility;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@access` tag documents the visibility of an element.
 *
 * ```
 * "@access" <AccessVisibility> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class AccessTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'access';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(VisibilityCombinator::NAME, 'access', 'AccessVisibility'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@access` tag documents the visibility of an element.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): AccessTag
    {
        /** @var Visibility */
        $access = $result->get('access');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new AccessTag(
            name: $name,
            access: $access,
            description: $description,
        );
    }
}
