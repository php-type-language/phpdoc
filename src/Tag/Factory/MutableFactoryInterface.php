<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Factory;

interface MutableFactoryInterface extends FactoryInterface
{
    /**
     * Registers a handler for tags.
     *
     * If you want to use your own tags then you can use this method to
     * instruct the {@see FactoryInterface} to register the name of a tag with
     * the custom {@see FactoryInterface} to which processing of this tag will
     * be delegated.
     *
     * @param non-empty-string|list<non-empty-string> $tags
     */
    public function register(string|array $tags, FactoryInterface $delegate): void;
}
