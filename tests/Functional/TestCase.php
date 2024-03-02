<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Functional;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PHPDoc\Tests\TestCase as BaseTestCase;

#[Group('functional'), Group('type-lang/phpdoc')]
abstract class TestCase extends BaseTestCase {}
