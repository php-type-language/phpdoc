<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;

abstract class TagDefinition implements TagDefinitionInterface
{
    public function __construct(
        /**
         * Canonical tag name
         *
         * @var non-empty-string
         */
        public readonly string $name,
        /**
         * Tag definition specification: shape of the tag body
         */
        public readonly RuleInterface $spec,
        /**
         * Where the tag may appear: only inline, only as a block tag,
         * or anywhere
         */
        public readonly TagPlacement $placement = TagPlacement::DEFAULT,
        /**
         * What the tag means, in markdown, {@see null} in case of it is not
         * described
         *
         * @var non-empty-string|null
         */
        public readonly ?string $description = null,
    ) {}

    public function __toString(): string
    {
        return \sprintf('"@%s" %s', $this->name, $this->spec);
    }
}
