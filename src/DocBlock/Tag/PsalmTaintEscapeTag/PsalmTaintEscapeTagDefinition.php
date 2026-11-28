<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintEscapeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-taint-escape` tag marks a value as no longer tainted after
 * passing through the described element.
 *
 * ```
 * "@psalm-taint-escape" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmTaintEscapeTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-taint-escape';
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
                The `@psalm-taint-escape` tag marks a value as no longer tainted after
                passing through the described element.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmTaintEscapeTag
    {
        /** @var non-empty-string */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PsalmTaintEscapeTag(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
    }
}
