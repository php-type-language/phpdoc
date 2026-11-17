<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use TypeLang\PhpDoc\Build\Model\LinkedPackage;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Platform\PlatformInterface;

/**
 * The platform a package of the model describes, built from the existing
 * definition and combinator classes.
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final class ModelPlatform implements PlatformInterface
{
    public function __construct(
        private readonly LinkedPackage $package,
    ) {}

    /**
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        foreach ($this->package->tags as $name => $tag) {
            $definition = new ($tag->getDefinitionClass())();

            \assert($definition instanceof TagDefinitionInterface);

            yield $name => $definition;
        }
    }

    /**
     * @return iterable<non-empty-string, non-empty-string>
     */
    public function getAliases(): iterable
    {
        return $this->package->aliases;
    }

    /**
     * @return iterable<non-empty-string, CombinatorType>
     */
    public function getCombinators(): iterable
    {
        foreach ($this->package->combinators as $combinator) {
            $instance = new ($combinator->class)();

            \assert(\is_callable($instance));

            yield $combinator->name => $instance;
        }
    }
}
