<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Concerns;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Traverser;

/**
 * @mixin TestCase
 */
trait InteractWithParser
{
    protected static function getTypeStatementAsString(TypeStatement $type): ?string
    {
        Traverser::new([$visitor = new Traverser\StringDumperVisitor()])
            ->traverse([$type]);

        return \trim($visitor->getOutput());
    }

    protected static function assertTypeStatementSame(TypeStatement $type, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($type));

        Assert::assertSame(\trim($expected), $actual, $message);
    }

    protected static function assertTypeStatementNotSame(TypeStatement $type, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($type));

        Assert::assertNotSame(\trim($expected), $actual, $message);
    }
}
