<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicMethodsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-forbid-undeclared-magic-methods` tag forbids calling
 * undeclared magic methods on the class it decorates, so only documented
 * methods may be called.
 *
 * ```
 * "@phan-forbid-undeclared-magic-methods" [ <Description> ]
 * ```
 */
final class PhanForbidUndeclaredMagicMethodsTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-forbid-undeclared-magic-methods';

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

    public function create(string $name, TagPayload $result): PhanForbidUndeclaredMagicMethodsTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanForbidUndeclaredMagicMethodsTag($name, $description);
    }
}
