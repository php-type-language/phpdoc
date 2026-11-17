<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicMethodsTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-forbid-undeclared-magic-methods` tag forbids calling
 * undeclared magic methods on the class it decorates, so only documented
 * methods may be called.
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanForbidUndeclaredMagicMethodsTag extends FlagTag {}
