<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@throws`" tag indicates that a function or method is able to throw
 * a specific type of "\Throwable" (an exception or an error).
 *
 * The "`@throws`" tag MAY be followed by a description explaining when and why
 * the "\Throwable" is thrown.
 *
 * ```
 * "@throws" <Type> [ <Description> ]
 * ```
 */
final class ThrowsTagDefinition extends TagDefinition
{
    public const string NAME = 'throws';

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
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): ThrowsTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ThrowsTag($name, $type, $description);
    }
}
