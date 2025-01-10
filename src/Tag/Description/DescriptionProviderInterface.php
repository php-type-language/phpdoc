<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

interface DescriptionProviderInterface extends OptionalDescriptionProviderInterface
{
    /**
     * Returns description object which can be represented as a string and
     * contains additional information.
     */
    public function getDescription(): DescriptionInterface;
}
