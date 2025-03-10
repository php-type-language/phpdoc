<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\LinkTag\LinkTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\MethodTag\MethodTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ParamTag\ParamTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyReadTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ReturnTag\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\SeeTag\SeeTagFactory;
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
        yield 'link' => new LinkTagFactory();
        yield 'method' => new MethodTagFactory($types);
        yield 'param' => new ParamTagFactory($types);
        yield 'property' => new PropertyTagFactory($types);
        yield 'property-read' => new PropertyReadTagFactory($types);
        yield 'property-write' => new PropertyWriteTagFactory($types);
        yield 'return' => new ReturnTagFactory($types);
        yield 'see' => new SeeTagFactory($types);
        yield 'template' => new TemplateTagFactory($types);
        yield ['template-implements', 'implements'] => new TemplateImplementsTagFactory($types);
        yield ['template-extends', 'extends'] => new TemplateExtendsTagFactory($types);
        yield 'template-covariant' => new TemplateCovariantTagFactory($types);
        yield 'throws' => new ThrowsTagFactory($types);
        yield 'var' => new VarTagFactory($types);
    }
}
