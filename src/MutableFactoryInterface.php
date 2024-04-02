<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

interface MutableFactoryInterface extends FactoryInterface
{
    /**
     * @param non-empty-string|list<non-empty-string> $tags
     */
    public function add(string|array $tags, FactoryInterface $delegate): void;
}
