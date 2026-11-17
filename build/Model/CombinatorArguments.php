<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

use TypeLang\Parser\TypeParser;

/**
 * The single dependency the generated code passes to a combinator of the
 * parser: the type parser of the docblock parser.
 */
final class CombinatorArguments
{
    public static function isTypeParser(\ReflectionParameter $parameter): bool
    {
        $type = $parameter->getType();

        return $type instanceof \ReflectionNamedType && $type->getName() === TypeParser::class;
    }

    /**
     * Whether the constructor of the combinator asks for the type parser.
     *
     * @param class-string $class
     */
    public static function needsTypeParser(string $class): bool
    {
        $constructor = (new \ReflectionClass($class))->getConstructor();

        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            if (self::isTypeParser($parameter)) {
                return true;
            }
        }

        return false;
    }
}
