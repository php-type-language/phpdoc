<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Exception;

use TypeLang\Parser\Node\Stmt\TypeStatement;

interface InvalidTypedTagExceptionInterface extends DocBlockExceptionInterface
{
    public function getType(): ?TypeStatement;

    public function getTypeOffset(): int;
}
