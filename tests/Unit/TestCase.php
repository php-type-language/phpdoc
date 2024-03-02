<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PHPDoc\Tests\TestCase as BaseTestCase;

#[Group('unit'), Group('type-lang/phpdoc')]
abstract class TestCase extends BaseTestCase {}
