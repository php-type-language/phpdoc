<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@formatter:on` tag re-enables automatic code formatting from this
 * point on, pairing with an earlier `@formatter:off`.
 */
final class FormatterOnTag extends FlagTag {}
