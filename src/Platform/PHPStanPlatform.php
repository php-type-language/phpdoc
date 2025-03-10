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
use TypeLang\PHPDoc\DocBlock\Tag\ThrowsTag\ThrowsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTagFactory;

final class PHPStanPlatform extends Platform
{
    public function getName(): string
    {
        return 'PHPStan';
    }

    protected function load(TypesParserInterface $types): iterable
    {
        yield 'phpstan-method' => new MethodTagFactory($types);
        yield 'phpstan-param' => new ParamTagFactory($types);
        yield 'phpstan-property' => new PropertyTagFactory($types);
        yield 'phpstan-property-read' => new PropertyReadTagFactory($types);
        yield 'phpstan-property-write' => new PropertyWriteTagFactory($types);
        yield 'phpstan-return' => new ReturnTagFactory($types);
        yield 'phpstan-template' => new TemplateTagFactory($types);
        yield 'phpstan-implements' => new TemplateImplementsTagFactory($types);
        yield 'phpstan-extends' => new TemplateExtendsTagFactory($types);
        yield 'phpstan-use' => new TemplateExtendsTagFactory($types);
        yield 'phpstan-template-covariant' => new TemplateCovariantTagFactory($types);
        yield 'phpstan-template-contravariant' => new TemplateContravariantTagFactory($types);
        yield 'phpstan-throws' => new ThrowsTagFactory($types);
        yield 'phpstan-var' => new VarTagFactory($types);
    }
}
