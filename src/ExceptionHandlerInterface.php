<?php

declare(strict_types=1);

namespace TypeLang\Reader;

interface ExceptionHandlerInterface
{
    public function throw(\Throwable $e): void;
}
