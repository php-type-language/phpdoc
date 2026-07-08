<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LanguageTag;

use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;

/**
 * The `@language` tag injects a foreign-language grammar — such as SQL,
 * HTML or a regular expression — into a string literal, so an IDE can
 * apply the right highlighting and completion inside it.
 */
final class LanguageTag extends IdentifierTag {}
