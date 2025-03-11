<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;

final class EmptyPlatform extends Platform
{
    public function getName(): string
    {
        return 'Empty';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        return [];
    }
}
