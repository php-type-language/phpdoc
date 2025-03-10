<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\ReturnTag\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateExtendsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateImplementsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ThrowsTag\ThrowsTagFactory;

final class FallbacksPlatform extends Platform
{
    public function getName(): string
    {
        return 'Fallbacks';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'returns' => new ReturnTagFactory($types);
        yield 'template-implements' => new TemplateImplementsTagFactory($types);
        yield ['template-extends', 'inherits'] => new TemplateExtendsTagFactory($types);
        yield ['template-use'] => new TemplateExtendsTagFactory($types);
        yield 'throw' => new ThrowsTagFactory($types);
    }
}
