<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Definition;

final class UnknownDefinition extends Definition
{
    public function getName(): string
    {
        return '<unknown>';
    }

    public function getDescription(): string
    {
        return 'This definition does not represent the actual physical '
             . 'embodiment of any tag and is a representation of any '
             . 'unknown tag.';
    }
}
