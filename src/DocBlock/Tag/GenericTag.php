<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * This tag is created if the docblock is not supported by parser but looks
 * like a correct docblock.
 */
final class GenericTag extends Tag implements InvalidTagInterface {}
