<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​deprecated` tag marks an element as deprecated, optionally since
 * a given version.
 *
 * ```
 * "@​deprecated" [ <Version> ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class DeprecatedTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'deprecated';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::maybe(
                    Spec::rule(VersionCombinator::NAME, 'version'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@deprecated` tag marks an element as deprecated, optionally since
                a given version.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): DeprecatedTag
    {
        /** @var non-empty-string|null */
        $version = $result->find('version');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new DeprecatedTag(
            name: $name,
            version: $version,
            description: $description,
        );
    }
}
