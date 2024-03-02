<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Definition;

use TypeLang\PHPDoc\Tag\VarTag;

/**
 * @template-implements ParsableInterface<VarTag>
 */
final class VarDefinition extends Definition implements ParsableInterface
{
    public function __construct()
    {
        $this->loadAnyLanguageAwarePlatformVersions();
    }

    public function getName(): string
    {
        return 'var';
    }

    public function getDescription(): string
    {
        return 'Describes the type of the constants, properties and variables';
    }

    public function parse(string $name, string $payload): VarTag
    {
        return new VarTag($this, $name, $payload);
    }
}
