<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\Parser\Node\SerializableKind;

enum ReferenceKind: int implements SerializableInterface
{
    use SerializableKind;

    case UNKNOWN = 0;
    case GENERIC_KIND = 1;
    case FUNCTION_KIND = 2;
    case NAME_KIND = 3;
    case URI_KIND = 4;
    case CLASS_CONST_KIND = 5;
    case CLASS_PROPERTY_KIND = 6;
    case CLASS_METHOD_KIND = 7;
}
