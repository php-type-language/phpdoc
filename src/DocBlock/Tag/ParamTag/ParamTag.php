<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@param` tag documents a single argument of a function or method.
 */
final class ParamTag extends TypedVariableTag
{
    /**
     * @param non-empty-string $variable
     */
    public function __construct(
        string $name,
        TypeReference $statement,
        string $variable,
        ?DescriptionInterface $description = null,
        /**
         * Whether the variable is written as `&$name`.
         */
        public readonly bool $isReference = false,
        /**
         * Whether the variable is written as `...$name`.
         */
        public readonly bool $isVariadic = false,
    ) {
        parent::__construct($name, $statement, $variable, $description);
    }

    #[\Override]
    protected function printVariable(): string
    {
        $prefix = '';

        if ($this->isReference) {
            $prefix .= '&';
        }

        if ($this->isVariadic) {
            $prefix .= '...';
        }

        return $prefix . parent::printVariable();
    }
}
