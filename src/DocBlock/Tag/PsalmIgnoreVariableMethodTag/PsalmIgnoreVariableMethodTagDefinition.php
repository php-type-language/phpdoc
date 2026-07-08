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
 */
final class PsalmIgnoreVariableMethodTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-ignore-variable-method';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): PsalmIgnoreVariableMethodTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmIgnoreVariableMethodTag($name, $description);
    }
}
