<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\MethodTag\MethodTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ParamTag\ParamTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyReadTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ReturnTag\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateExtendsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateImplementsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ThrowsTag\ThrowsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTagFactory;

final class StandardPlatform extends Platform
{
    public function getName(): string
    {
        return 'standard';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'method' => new MethodTagFactory();
        yield 'param' => new ParamTagFactory();
        yield 'property' => new PropertyTagFactory();
        yield 'property-read' => new PropertyReadTagFactory();
        yield 'property-write' => new PropertyWriteTagFactory();
        yield 'return' => new ReturnTagFactory();
        yield 'throws' => new ThrowsTagFactory();
        yield 'var' => new VarTagFactory();
        yield 'template' => new TemplateTagFactory();
        yield ['template-implements', 'implements'] => new TemplateImplementsTagFactory();
        yield ['template-extends', 'extends'] => new TemplateExtendsTagFactory();
        yield 'template-covariant' => new TemplateCovariantTagFactory();
    }
}
