<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanConstructorUsedForSideEffectsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-constructor-used-for-side-effects` tag declares that a
 * constructor's return value is intentionally discarded by callers,
 * suppressing the issue raised for an unused `new` expression.
 *
 * ```
 * "@phan-constructor-used-for-side-effects" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanConstructorUsedForSideEffectsTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phan-constructor-used-for-side-effects';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): PhanConstructorUsedForSideEffectsTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhanConstructorUsedForSideEffectsTag(
            name: $name,
            description: $description,
        );
    }
}
