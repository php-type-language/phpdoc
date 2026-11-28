<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@pure-unless-callable-is-impure` tag declares a function pure unless a
 * callable it receives is itself impure.
 *
 * ```
 * "@pure-unless-callable-is-impure" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class PureUnlessCallableIsImpureTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'pure-unless-callable-is-impure';
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
                The `@pure-unless-callable-is-impure` tag declares a function pure unless a
                callable it receives is itself impure.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PureUnlessCallableIsImpureTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PureUnlessCallableIsImpureTag(
            name: $name,
            description: $description,
        );
    }
}
