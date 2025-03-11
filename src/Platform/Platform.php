<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;

abstract class Platform implements PlatformInterface
{
    public function __construct(
        protected readonly TypesParserInterface $types = new TypesParser(tolerant: true),
    ) {
        if ($this->types instanceof TypesParser && $this->types->tolerant === false) {
            throw new \InvalidArgumentException('Tolerant parser mode required');
        }
    }

    public function getTags(): iterable
    {
        foreach ($this->load($this->types) as $tag => $factory) {
            if (\is_iterable($tag)) {
                foreach ($tag as $name) {
                    yield $name => $factory;
                }
            } else {
                yield $tag => $factory;
            }
        }
    }

    /**
     * @return iterable<non-empty-lowercase-string|iterable<mixed, non-empty-lowercase-string>, TagFactoryInterface>
     */
    abstract protected function load(TypesParserInterface $types): iterable;
}
