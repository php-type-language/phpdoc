<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\MixinTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@mixin`" tag declares that the members of the referenced type are
 * magically available on the described class.
 *
 * ```
 * "@mixin" <Type> [ <Description> ]
 * ```
 */
final class MixinTagDefinition extends TagDefinition
{
    public const string NAME = 'mixin';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): MixinTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new MixinTag($name, $type, $description);
    }
}
