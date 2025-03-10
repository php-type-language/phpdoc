<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase as BaseTestCase;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Traverser;

abstract class TestCase extends BaseTestCase
{
    /**
     * @api
     */
    protected static function getTypeStatementAsArray(TypeStatement $type): array
    {
        $json = \json_encode($type);

        return \json_decode($json, true, flags: \JSON_THROW_ON_ERROR);
    }

    /**
     * @api
     */
    protected static function getTypeStatementAsString(TypeStatement $type): ?string
    {
        Traverser::new([$visitor = new Traverser\StringDumperVisitor()])
            ->traverse([$type]);

        return \trim($visitor->getOutput());
    }

    /**
     * @api
     */
    protected static function assertTypeStatementSame(TypeStatement $type, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($type));

        Assert::assertSame(\trim($expected), $actual, $message);
    }

    /**
     * @api
     */
    protected static function assertTypeStatementNotSame(TypeStatement $type, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($type));

        Assert::assertNotSame(\trim($expected), $actual, $message);
    }
}
