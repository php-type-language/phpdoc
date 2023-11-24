<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * This tag is created if a parsing error occurs while parsing the original
 * tag that contain {@see TypeStatement} reference.
 */
final class InvalidTypedTag extends TypedTag implements InvalidTagInterface {}
