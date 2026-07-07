<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * A generic type parameter declaration: a name with an optional bound and an
 * optional default type.
 */
abstract class TypeParameterTagDefinition extends TagDefinition
{
    /**
     * @param non-empty-string $name canonical name of the concrete tag
     */
    public function __construct(string $name)
    {
        parent::__construct(
            name: $name,
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'parameter'),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('of'),
                        Spec::rule(TypeCombinator::NAME, 'bound'),
                    ),
                ),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('='),
                        Spec::rule(TypeCombinator::NAME, 'default'),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    final public function create(string $name, TagPayload $result): TypeParameterTag
    {
        /** @var non-empty-string $parameter */
        $parameter = $result->get('parameter');

        /** @var TypeReference|null $bound */
        $bound = $result->find('bound');

        /** @var TypeReference|null $default */
        $default = $result->find('default');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return $this->make($name, $parameter, $bound, $default, $description);
    }

    /**
     * @param non-empty-string $name the tag name, without the leading "@"
     * @param non-empty-string $parameter
     */
    abstract protected function make(
        string $name,
        string $parameter,
        ?TypeReference $bound,
        ?TypeReference $default,
        ?DescriptionInterface $description,
    ): TypeParameterTag;
}
