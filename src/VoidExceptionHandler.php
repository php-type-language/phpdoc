<?php

declare(strict_types=1);

namespace TypeLang\Reader;

final class VoidExceptionHandler implements ExceptionHandlerInterface
{
    public function throw(\Throwable $e): void
    {
        // NO OP
    }
}
