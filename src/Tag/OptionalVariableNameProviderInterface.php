<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface OptionalVariableNameProviderInterface
{
    /**
     * Returns the name of the variable (parameter, field, etc.) to
     * which this tag is attached or {@see null} in case of the tag does
     * not contain a name.
     *
     * @psalm-immutable Each call to the method must return the same value.
     *
     * @return non-empty-string|null
     */
    public function getVariable(): ?string;
}
