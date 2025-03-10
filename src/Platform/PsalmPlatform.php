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
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTagFactory;

final class PsalmPlatform extends Platform
{
    public function getName(): string
    {
        return 'Psalm';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'psalm-method' => new MethodTagFactory($types);
        yield 'psalm-param' => new ParamTagFactory($types);
        yield 'psalm-property' => new PropertyTagFactory($types);
        yield 'psalm-property-read' => new PropertyReadTagFactory($types);
        yield 'psalm-property-write' => new PropertyWriteTagFactory($types);
        yield 'psalm-return' => new ReturnTagFactory($types);
        yield 'psalm-template' => new TemplateTagFactory($types);
        yield 'psalm-implements' => new TemplateImplementsTagFactory($types);
        yield 'psalm-extends' => new TemplateExtendsTagFactory($types);
        yield 'psalm-use' => new TemplateExtendsTagFactory($types);
        yield 'psalm-template-covariant' => new TemplateCovariantTagFactory($types);
        yield 'psalm-template-contravariant' => new TemplateContravariantTagFactory($types);
        yield 'psalm-var' => new VarTagFactory($types);
    }
}
