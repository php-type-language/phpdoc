<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\MethodTag;

use TypeLang\PhpDoc\DocBlock\Combinator\CallableTypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\Type\CallableTypeNode;

/**
 * The "`@method`" tag declares a "magic" method that can be called on the
 * described class.
 *
 * The method is written as a callable, either carrying its own return type or
 * having it spelled out in front of the signature.
 *
 * ```
 * "@method" [ "static" ] [ <ReturnType> ] <Callable> [ <Description> ]
 * ```
 */
final class MethodTagDefinition extends TagDefinition
{
    public const string NAME = 'method';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::maybe(
                    Spec::literal('static', 'static'),
                ),
                Spec::oneOf(
                    Spec::sequence(
                        Spec::rule(TypeCombinator::NAME, 'return-type'),
                        Spec::rule(CallableTypeCombinator::NAME, 'callable'),
                    ),
                    Spec::rule(CallableTypeCombinator::NAME, 'callable'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): MethodTag
    {
        $isStatic = $result->find('static') !== null;

        /** @var TypeReference $callable */
        $callable = $result->get('callable');

        /** @var TypeReference|null $returnType */
        $returnType = $result->find('return-type');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        $method = $callable->type;

        // Guaranteed by the callable combinator; guards the type for the graft.
        if (!$method instanceof CallableTypeNode) {
            throw new \LogicException('Expected a callable type');
        }

        // A leading return type applies only when the signature omits its own.
        // TODO Throw an exception otherwise in case of:
        //      1) $returnType !== null
        //      2) $method->type !== null
        if ($returnType !== null && $method->type === null) {
            $method->type = $returnType->type;
        }

        $rendered = $returnType === null
            ? (string) $callable
            : \sprintf('%s %s', $returnType, $callable);

        return new MethodTag($name, $method, $rendered, $isStatic, $description);
    }
}
