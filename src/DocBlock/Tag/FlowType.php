<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * The kind of taint flow a `@psalm-flow` tag describes.
 */
enum FlowType: string
{
    case TaintSource = 'TaintSource';
    case TaintSink = 'TaintSink';
    case TaintSpecialize = 'TaintSpecialize';
    case TaintUnescape = 'TaintUnescape';
}
