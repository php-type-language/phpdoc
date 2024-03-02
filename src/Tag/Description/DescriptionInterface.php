<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

use TypeLang\Parser\Node\SerializableInterface;

interface DescriptionInterface extends SerializableInterface, \Stringable {}
