<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSideEffectFreeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-side-effect-free` tag declares a function or method as free
 * of side effects.
 *
 * ```
 * "@phan-side-effect-free" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanSideEffectFreeTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phan-side-effect-free';
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
                The `@phan-side-effect-free` tag declares a function or method as free
                of side effects.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PhanSideEffectFreeTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhanSideEffectFreeTag(
            name: $name,
            description: $description,
        );
    }
}
