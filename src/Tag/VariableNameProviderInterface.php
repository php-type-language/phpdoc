<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface VariableNameProviderInterface extends OptionalVariableNameProviderInterface
{
    /**
     * Returns the name of the variable (parameter, field, etc.) to
     * which this tag is attached.
     *
     * @return non-empty-string
     */
    public function getVariableName(): string;
}
