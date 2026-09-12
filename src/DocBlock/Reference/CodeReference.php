<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference pointing to an element of the described codebase, such as
 * a class, function or class member.
 *
 * References of this kind are always considered internal, so they are never
 * external.
 */
abstract class CodeReference implements ReferenceInterface
{
    public readonly bool $isExternal;

    public function __construct()
    {
        $this->isExternal = false;
    }
}
