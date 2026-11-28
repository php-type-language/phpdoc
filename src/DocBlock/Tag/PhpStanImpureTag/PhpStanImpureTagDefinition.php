<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanImpureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phpstan-impure` tag declares a function or method as impure, that
 * is, having side effects.
 *
 * ```
 * "@phpstan-impure" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstan.xml, edit the model instead
 */
final class PhpStanImpureTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phpstan-impure';
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
                The `@phpstan-impure` tag declares a function or method as impure, that
                is, having side effects.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PhpStanImpureTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhpStanImpureTag(
            name: $name,
            description: $description,
        );
    }
}
