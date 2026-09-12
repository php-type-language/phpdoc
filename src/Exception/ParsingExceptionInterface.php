<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

/**
 * Error occurring while processing phpdoc content.
 *
 * @property-read string $source Gets the full docblock content in which the error occurred.
 * @property-read int<0, max> $offset Gets the offset at which the error occurred.
 */
interface ParsingExceptionInterface extends PhpDocExceptionInterface {}
