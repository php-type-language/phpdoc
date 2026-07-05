<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Description;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class DescriptionTest extends TestCase
{
    #[Test]
    public function constructorStoresValue(): void
    {
        $description = new Description('Some text');

        self::assertSame('Some text', $description->value);
    }

    #[Test]
    public function valueDefaultsToEmptyString(): void
    {
        self::assertSame('', new Description()->value);
    }

    #[Test]
    public function implementsDescriptionInterface(): void
    {
        self::assertInstanceOf(DescriptionInterface::class, new Description());
    }
}
