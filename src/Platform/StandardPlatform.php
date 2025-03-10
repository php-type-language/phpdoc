<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\AbstractTag\AbstractTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\FinalTag\FinalTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\IgnoreTag\IgnoreTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\InheritDocTag\InheritDocTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\LicenseTag\LicenseTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\LinkTag\LinkTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\MethodTag\MethodTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\NoNamedArgumentsTag\NoNamedArgumentsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ParamTag\ParamTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyReadTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ReturnTag\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\SeeTag\SeeTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateExtendsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag\TemplateImplementsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateTag\TemplateTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\ThrowsTag\ThrowsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTagFactory;

final class StandardPlatform extends Platform
{
    public function getName(): string
    {
        return 'Standard';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'abstract' => new AbstractTagFactory();
        yield 'extends' => new TemplateExtendsTagFactory($types);
        yield 'final' => new FinalTagFactory();
        yield 'implements' => new TemplateImplementsTagFactory($types);
        yield 'inheritdoc' => new InheritDocTagFactory();
        yield 'ignore' => new IgnoreTagFactory();
        yield 'license' => new LicenseTagFactory();
        yield 'link' => new LinkTagFactory();
        yield 'method' => new MethodTagFactory($types);
        yield 'no-named-arguments' => new NoNamedArgumentsTagFactory();
        yield 'param' => new ParamTagFactory($types);
        yield 'property' => new PropertyTagFactory($types);
        yield 'property-read' => new PropertyReadTagFactory($types);
        yield 'property-write' => new PropertyWriteTagFactory($types);
        yield 'return' => new ReturnTagFactory($types);
        yield 'see' => new SeeTagFactory($types);
        yield 'template' => new TemplateTagFactory($types);
        yield 'template-contravariant' => new TemplateContravariantTagFactory($types);
        yield 'template-covariant' => new TemplateCovariantTagFactory($types);
        yield 'use' => new TemplateExtendsTagFactory($types);
        yield 'throws' => new ThrowsTagFactory($types);
        yield 'var' => new VarTagFactory($types);

        yield from $this->loadAliases($types);
    }

    /**
     * @return iterable<non-empty-string|iterable<mixed, non-empty-string>, TagFactoryInterface>
     */
    protected function loadAliases(TypesParserInterface $types): iterable
    {
        yield 'inherits' => $extends = new TemplateExtendsTagFactory($types);
        yield 'returns' => new ReturnTagFactory($types);
        yield 'template-extends' => $extends;
        yield 'template-implements' => new TemplateImplementsTagFactory($types);
        yield 'template-use' => new TemplateExtendsTagFactory($types);
        yield 'throw' => new ThrowsTagFactory($types);
    }
}
