<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface VariableNameProviderInterface extends OptionalVariableNameProviderInterface
{
    /**
     * Returns the name of the variable (parameter, field, etc.) to
     * which this tag is attached.
     *
     * @psalm-immutable Each call to the method must return the same value.
     *
     * @return non-empty-string
     */
    public function getVariable(): string;
}
