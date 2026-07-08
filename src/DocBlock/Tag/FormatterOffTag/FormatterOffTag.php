<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FormatterOffTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@formatter:off` tag disables automatic code formatting from this
 * point on, until a matching `@formatter:on` is found further down the
 * file.
 */
final class FormatterOffTag extends FlagTag {}
