<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * This tag is created if a parsing error occurs while parsing the original tag.
 */
final class InvalidTag extends Tag implements InvalidTagInterface {}
