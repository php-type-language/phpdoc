<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreLineTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​phpstan-ignore-line` tag silences any error reported on the
 * current line.
 *
 * ```
 * "@​phpstan-ignore-line" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstan.xml, edit the model instead
 */
final class PhpStanIgnoreLineTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phpstan-ignore-line';
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

    public function create(string $name, TagPayload $result): PhpStanIgnoreLineTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhpStanIgnoreLineTag(
            name: $name,
            description: $description,
        );
    }
}
