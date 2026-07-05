<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AccessTag;

/**
 * The access level (visibility) an "@access" tag may declare.
 */
enum Visibility: string
{
    case Public = 'public';
    case Protected = 'protected';
    case Private = 'private';
}
