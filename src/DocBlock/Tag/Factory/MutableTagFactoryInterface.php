<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

interface MutableTagFactoryInterface extends TagFactoryInterface
{
    /**
     * Registers a handler for tags.
     *
     * If you want to use your own tags then you can use this method to
     * instruct the {@see TagFactoryInterface} to register the name of a tag with
     * the custom {@see TagFactoryInterface} to which processing of this tag will
     * be delegated.
     *
     * @param non-empty-lowercase-string|list<non-empty-lowercase-string> $tags
     */
    public function register(string|array $tags, TagFactoryInterface $delegate): void;
}
