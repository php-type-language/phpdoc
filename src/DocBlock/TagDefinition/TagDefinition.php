<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;

abstract class TagDefinition implements TagDefinitionInterface
{
    public function __construct(
        /**
         * @var non-empty-string
         */
        public readonly string $name,
        public readonly RuleInterface $spec,
        public readonly TagPlacement $placement = TagPlacement::DEFAULT,
    ) {}

    public function __toString(): string
    {
        return \sprintf('"@%s" %s', $this->name, $this->spec);
    }
}
