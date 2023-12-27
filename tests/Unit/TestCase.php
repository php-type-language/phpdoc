<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PhpDocParser\Tests\TestCase as BaseTestCase;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
abstract class TestCase extends BaseTestCase {}
