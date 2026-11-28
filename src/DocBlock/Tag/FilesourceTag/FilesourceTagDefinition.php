<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FilesourceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@filesource` tag tells documentation tooling to include the source of
 * the current file in its output.
 *
 * ```
 * "@filesource" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class FilesourceTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'filesource';
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
                The `@filesource` tag tells documentation tooling to include the source of
                the current file in its output.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): FilesourceTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new FilesourceTag(
            name: $name,
            description: $description,
        );
    }
}
