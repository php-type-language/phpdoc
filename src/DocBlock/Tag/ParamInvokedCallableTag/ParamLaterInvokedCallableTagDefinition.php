<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * The `@param-later-invoked-callable` tag marks a callable argument that is
 * invoked after the function or method returns.
 *
 * ```
 * "@param-later-invoked-callable" <Variable> [ <Description> ]
 * ```
 */
final class ParamLaterInvokedCallableTagDefinition extends ParamInvokedCallableTagDefinition
{
    public const string NAME = 'param-later-invoked-callable';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        string $variable,
        ?DescriptionInterface $description,
    ): ParamLaterInvokedCallableTag {
        return new ParamLaterInvokedCallableTag($name, $variable, $description);
    }
}
