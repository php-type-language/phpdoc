<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

interface ExceptionHandlerInterface
{
    public function throw(\Throwable $e): void;
}
