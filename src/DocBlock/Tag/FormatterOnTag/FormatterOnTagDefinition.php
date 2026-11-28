<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​formatter:on` tag re-enables automatic code formatting from this
 * point on, pairing with an earlier `@​formatter:off`.
 *
 * ```
 * "@​formatter:on" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstorm.xml, edit the model instead
 */
final class FormatterOnTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'formatter:on';
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
                The `@formatter:on` tag re-enables automatic code formatting from this
                point on, pairing with an earlier `@formatter:off`.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): FormatterOnTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new FormatterOnTag(
            name: $name,
            description: $description,
        );
    }
}
