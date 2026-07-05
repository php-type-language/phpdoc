<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * A tag that marks an element and carries no value beyond an optional
 * description.
 */
abstract class FlagTag extends Tag implements FlagTagInterface {}
