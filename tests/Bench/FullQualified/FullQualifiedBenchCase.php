<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

abstract class FullQualifiedBenchCase
{
    public const PARSING_SAMPLES = [
        'number_of()                  Testing',
        'number_of()',
        'Path\To\Function\number_of() Description',
        'MyClass::$items              Property',
        'Path\To\MyClass::$items      For the property whose items are counted.',
        'MyClass::setItems()          To set the items for this collection.',
        'Example\MyClass::setItems()',
        'https://example.com/my/bar link',
        'Some::ANY_* test',
        'non-empty-string\ololo::ANY_* test',
        'Some::ANY_*_OLOLO test42',
        'PSR\Documentation\API',
        'doc://getting-started/index Getting started document.',
    ];

    abstract public function benchIdentifierReading(): void;
}
