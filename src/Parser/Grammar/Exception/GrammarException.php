<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Exception;

use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;

abstract class GrammarException extends \RuntimeException implements
    PhpDocExceptionInterface {}
