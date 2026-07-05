<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * A tag that refers to a variable.
 */
interface VariableTagInterface extends TagInterface
{
    /**
     * The referenced variable name, without the leading "$".
     *
     * @var non-empty-string
     */
    public string $variable {
        get;
    }
}
