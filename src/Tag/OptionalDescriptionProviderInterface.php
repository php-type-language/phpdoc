<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface OptionalDescriptionProviderInterface
{
    /**
     * Returns description object which can be represented as a string and
     * contains additional information.
     *
     * @psalm-immutable Each call to the method must return the same value.
     */
    public function getDescription(): ?DescriptionInterface;
}
