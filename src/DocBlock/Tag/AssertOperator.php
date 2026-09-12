<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * The way an assertion relates the subject to the type it carries.
 *
 * The type of an assertion may be written with a prefix, which is what tells
 * the four of them apart:
 *
 * ```
 *  "@assert"  T $value    the value is of the type
 *  "@assert" !T $value    the value is anything but the type
 *  "@assert" =T $value    the value is the very one the type describes
 *  "@assert" !=T $value   the value is anything but the one it describes
 * ```
 */
enum AssertOperator: string
{
    /**
     * The subject is of the type.
     */
    case Is = '';

    /**
     * The subject is anything but the type.
     */
    case IsNot = '!';

    /**
     * The subject is the very value the type describes, rather than merely
     * being of that type.
     */
    case Equals = '=';

    /**
     * The subject is anything but the value the type describes.
     */
    case NotEquals = '!=';
}
