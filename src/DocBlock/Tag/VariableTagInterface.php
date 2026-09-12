<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * A tag that refers to a variable.
 *
 * @property-read non-empty-string $variable The referenced variable name, without the leading "$".
 */
interface VariableTagInterface extends TagInterface {}
