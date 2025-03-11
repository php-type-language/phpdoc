<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\DocBlockParser;

abstract class DocBlockParserBenchCase
{
    /**
     * @var non-empty-list<non-empty-string>
     */
    protected readonly array $samples;

    public function __construct()
    {
        $this->samples = \json_decode(
            json: \file_get_contents(__DIR__ . '/samples.json'),
            associative: true,
            flags: \JSON_THROW_ON_ERROR,
        );
    }

    abstract public function benchDocBlockParsing(): void;
}
