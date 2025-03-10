<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\AbstractTag\AbstractTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\MethodTag\MethodTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ParamTag\ParamTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyReadTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ReturnTag\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateExtendsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTagFactory;

final class PhanPlatform extends Platform
{
    public function getName(): string
    {
        return 'Phan';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'phan-abstract' => new AbstractTagFactory();
        yield 'phan-method' => new MethodTagFactory($types);
        yield 'phan-param' => new ParamTagFactory($types);
        yield 'phan-property' => new PropertyTagFactory($types);
        yield 'phan-property-read' => new PropertyReadTagFactory($types);
        yield 'phan-property-write' => new PropertyWriteTagFactory($types);
        yield 'phan-return' => new ReturnTagFactory($types);
        yield 'phan-template' => new TemplateTagFactory($types);
        yield ['phan-inherits', 'phan-extends'] => new TemplateExtendsTagFactory($types);
        yield 'phan-var' => new VarTagFactory($types);
    }
}
