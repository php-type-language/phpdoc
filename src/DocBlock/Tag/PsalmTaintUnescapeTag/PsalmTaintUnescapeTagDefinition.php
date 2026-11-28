<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintUnescapeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-taint-unescape` tag marks a value as tainted again after
 * passing through the described element, reversing an earlier
 * `@psalm-taint-escape`.
 *
 * ```
 * "@psalm-taint-unescape" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmTaintUnescapeTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-taint-unescape';
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
                The `@psalm-taint-unescape` tag marks a value as tainted again after
                passing through the described element, reversing an earlier
                `@psalm-taint-escape`.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmTaintUnescapeTag
    {
        /** @var non-empty-string */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PsalmTaintUnescapeTag(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
    }
}
