<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Description;

/**
 * Representation of any entry that MAY contain an optional description.
 */
interface OptionalDescriptionProviderInterface
{
    /**
     * Gets optional description object which can be represented as
     * a {@see string} and contains additional information or {@see null}
     * in case of description is not defined in the entry.
     *
     * @readonly
     */
    public ?DescriptionInterface $description { get; }
}
