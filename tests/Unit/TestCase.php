<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase as BaseTestCase;
use TypeLang\Parser\Node\Node;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Traverser;

abstract class TestCase extends BaseTestCase
{
    /**
     * @api
     * @param Node|list<Node> $node
     * @throws \JsonException
     */
    protected static function getTypeStatementAsArray(Node|array $node): array
    {
        $json = \json_encode($node);

        return \json_decode($json, true, flags: \JSON_THROW_ON_ERROR);
    }

    /**
     * @api
     * @param Node|list<Node> $node
     */
    protected static function getTypeStatementAsString(Node|array $node): ?string
    {
        Traverser::new([$visitor = new Traverser\StringDumperVisitor()])
            ->traverse(\is_array($node) ? $node : [$node]);

        return \trim($visitor->getOutput());
    }

    /**
     * @api
     * @param Node|list<Node> $node
     */
    protected static function assertTypeStatementSame(Node|array $node, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($node));

        Assert::assertSame(\trim($expected), $actual, $message);
    }

    /**
     * @api
     * @param Node|list<Node> $node
     */
    protected static function assertTypeStatementNotSame(Node|array $node, string $expected, string $message = ''): void
    {
        $actual = \trim(static::getTypeStatementAsString($node));

        Assert::assertNotSame(\trim($expected), $actual, $message);
    }
}
