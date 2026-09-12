<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\ClassPropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableMethodReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariablePropertyReference;
use TypeLang\PhpDoc\DocBlock\Reference\VariableReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads what an assertion is written of.
 *
 * An assertion narrows a variable, but also a property or a method of
 * whatever that variable holds, and a static property of a class:
 *
 * ```
 *  $value
 *  $this->items
 *  $this->getItems()
 *  self::$instances
 * ```
 *
 * @template-implements CombinatorInterface<CodeReference>
 */
final class AssertSubjectCombinator implements CombinatorInterface
{
    public const NAME = 'AssertSubject';

    public function __invoke(Cursor $cursor): CodeReference
    {
        $subject = $this->parse($cursor);

        // A subject is a single word: nothing but whitespace may follow it.
        if ($subject === null || $cursor->readWord() !== '') {
            throw new NoMatchException('Expected a variable, a property or a method');
        }

        return $subject;
    }

    private function parse(Cursor $cursor): ?CodeReference
    {
        // "$value", "$this->items" and "$this->getItems()"
        if ($cursor->readLiteral('$')) {
            $variable = $cursor->readPhpIdentifier();

            if ($variable === '') {
                return null;
            }

            if (!$cursor->readLiteral('->')) {
                return new VariableReference($variable);
            }

            $member = $cursor->readPhpIdentifier();

            if ($member === '') {
                return null;
            }

            return $cursor->readLiteral('()')
                ? new VariableMethodReference($variable, $member)
                : new VariablePropertyReference($variable, $member);
        }

        // "self::$instances"
        $class = $cursor->readPhpQualifiedName();

        if ($class === '' || !$cursor->readLiteral('::$')) {
            return null;
        }

        $property = $cursor->readPhpIdentifier();

        return $property === '' ? null : new ClassPropertyReference($class, $property);
    }
}
