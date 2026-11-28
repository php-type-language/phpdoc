<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariableMethodTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-variable-method` tag suppresses "undefined method"
 * issues for methods called on the annotated variable.
 *
 * ```
 * "@psalm-ignore-variable-method" [ <Description> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmIgnoreVariableMethodTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-ignore-variable-method';
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
                The `@psalm-ignore-variable-method` tag suppresses "undefined method"
                issues for methods called on the annotated variable.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmIgnoreVariableMethodTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PsalmIgnoreVariableMethodTag(
            name: $name,
            description: $description,
        );
    }
}
