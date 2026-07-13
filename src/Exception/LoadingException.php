<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

abstract class LoadingException extends \LogicException implements
    PhpDocExceptionInterface {}
