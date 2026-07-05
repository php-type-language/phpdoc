<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\ClassConstantReference;
use TypeLang\PhpDoc\DocBlock\Reference\ClassMethodReference;
use TypeLang\PhpDoc\DocBlock\Reference\ClassPropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\FunctionReference;
use TypeLang\PhpDoc\DocBlock\Reference\SymbolReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a reference to a code element: a class, a function, a class method, a
 * class constant, a class property or a variable.
 */
final readonly class ReferenceCombinator implements CombinatorInterface
{
    public const string NAME = 'Reference';

    public function __invoke(Cursor $cursor): CodeReference
    {
        $reference = $this->parse($cursor);

        // A reference is a single word: nothing but whitespace may follow it.
        if ($reference === null || $cursor->readWord() !== '') {
            throw new NoMatchException('Expected a code reference');
        }

        return $reference;
    }

    private function parse(Cursor $cursor): ?CodeReference
    {
        // A variable: "$name".
        if ($cursor->readLiteral('$')) {
            $name = $cursor->readPhpIdentifier();

            return $name === '' ? null : new VariableReference($name);
        }

        $symbol = $cursor->readPhpQualifiedName();

        if ($symbol === '') {
            return null;
        }

        // A class member: "Class::member".
        if ($cursor->readLiteral('::')) {
            return $this->parseMember($cursor, $symbol);
        }

        // A function: "name()".
        if ($cursor->readLiteral('()')) {
            return new FunctionReference($symbol);
        }

        // A class or global constant: "name".
        return new SymbolReference($symbol);
    }

    /**
     * Parses the member of a "Class::member" reference.
     *
     * @param non-empty-string $class
     */
    private function parseMember(Cursor $cursor, string $class): ?CodeReference
    {
        // A property: "$name".
        if ($cursor->readLiteral('$')) {
            $name = $cursor->readPhpIdentifier();

            return $name === '' ? null : new ClassPropertyReference($class, $name);
        }

        $member = $cursor->readPhpIdentifier();

        if ($member === '') {
            return null;
        }

        // A method: "name()".
        if ($cursor->readLiteral('()')) {
            return new ClassMethodReference($class, $member);
        }

        // A constant: "name".
        return new ClassConstantReference($class, $member);
    }
}
