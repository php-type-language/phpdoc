<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * The `@param-immediately-invoked-callable` tag marks a callable argument
 * that is invoked before the function or method returns.
 *
 * ```
 * "@param-immediately-invoked-callable" <Variable> [ <Description> ]
 * ```
 */
final class ParamImmediatelyInvokedCallableTagDefinition extends ParamInvokedCallableTagDefinition
{
    public const string NAME = 'param-immediately-invoked-callable';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        string $variable,
        ?DescriptionInterface $description,
    ): ParamImmediatelyInvokedCallableTag {
        return new ParamImmediatelyInvokedCallableTag($name, $variable, $description);
    }
}
