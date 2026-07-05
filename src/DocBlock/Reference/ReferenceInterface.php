<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A target a documentation tag (like "{@see}" or "{@link}") points to.
 *
 * The target may either be an element of the described codebase or an
 * external resource located outside of it.
 */
interface ReferenceInterface extends \Stringable
{
    /**
     * Gets {@see true} when the reference points to a resource located
     * outside of the described codebase, such as an external web page.
     */
    public bool $isExternal {
        get;
    }
}
