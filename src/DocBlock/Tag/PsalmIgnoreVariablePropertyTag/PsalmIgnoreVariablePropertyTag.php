<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariablePropertyTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-ignore-variable-property` tag suppresses "undefined
 * property" issues for properties accessed on the annotated variable.
 */
final class PsalmIgnoreVariablePropertyTag extends FlagTag {}
