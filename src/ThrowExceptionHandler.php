<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

final class ThrowExceptionHandler implements ExceptionHandlerInterface
{
    public function throw(\Throwable $e): void
    {
        throw $e;
    }
}
