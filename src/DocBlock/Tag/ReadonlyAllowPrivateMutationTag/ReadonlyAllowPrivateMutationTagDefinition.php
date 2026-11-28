<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ReadonlyAllowPrivateMutationTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@readonly-allow-private-mutation` tag allows a readonly property to
 * be mutated from within the declaring class, rather than only during
 * initialization.
 *
 * ```
 * "@readonly-allow-private-mutation" [ <Description> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class ReadonlyAllowPrivateMutationTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'readonly-allow-private-mutation';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@readonly-allow-private-mutation` tag allows a readonly property to
                be mutated from within the declaring class, rather than only during
                initialization.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ReadonlyAllowPrivateMutationTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ReadonlyAllowPrivateMutationTag(
            name: $name,
            description: $description,
        );
    }
}
