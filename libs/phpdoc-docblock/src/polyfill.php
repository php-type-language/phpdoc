<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock;

if (\PHP_VERSION_ID >= 80400) {
    return;
}

\class_alias(Polyfill\Description\OptionalDescriptionProviderInterface::class,
    Description\OptionalDescriptionProviderInterface::class);

\class_alias(Polyfill\Description\DescriptionProviderInterface::class,
    Description\DescriptionProviderInterface::class);

\class_alias(Polyfill\Tag\OptionalTypeProviderInterface::class,
    Tag\OptionalTypeProviderInterface::class);

\class_alias(Polyfill\Tag\TypeProviderInterface::class,
    Tag\TypeProviderInterface::class);

\class_alias(Polyfill\Tag\OptionalVariableProviderInterface::class,
    Tag\OptionalVariableProviderInterface::class);

\class_alias(Polyfill\Tag\VariableProviderInterface::class,
    Tag\VariableProviderInterface::class);

\class_alias(Polyfill\Tag\TagsProviderInterface::class,
    Tag\TagsProviderInterface::class);

\class_alias(Polyfill\Tag\TagInterface::class,
    Tag\TagInterface::class);

\class_alias(Polyfill\Tag\InvalidTagInterface::class,
    Tag\InvalidTagInterface::class);

\class_alias(Polyfill\Description\TaggedDescriptionInterface::class,
    Description\TaggedDescriptionInterface::class);
