<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

interface MutableFactoryInterface extends FactoryInterface
{
    public function add(string|array $tags, FactoryInterface $delegate): void;
}
