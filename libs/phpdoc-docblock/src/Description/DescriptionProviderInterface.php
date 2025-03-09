<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Description;

/**
 * Representation of any entry that contain an description.
 */
interface DescriptionProviderInterface extends OptionalDescriptionProviderInterface
{
    /**
     * Gets description object which can be represented as a {@see string}
     * and contains additional information.
     *
     * @readonly
     */
    public DescriptionInterface $description { get; }
}
