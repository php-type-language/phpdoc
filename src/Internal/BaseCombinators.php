<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Internal;

use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\AssertSubjectCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\CallableTypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * The combinators the parser provides to the tags of every platform.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 *
 * @generated from resources/model/combinators.xml, edit the model instead
 */
final class BaseCombinators
{
    /**
     * @return array<non-empty-string, CombinatorType>
     */
    public static function create(TypeParser $typeParser): array
    {
        return [
            AssertSubjectCombinator::NAME => new AssertSubjectCombinator(),
            CallableTypeCombinator::NAME => new CallableTypeCombinator($typeParser),
            IntegerCombinator::NAME => new IntegerCombinator(),
            IssueNameCombinator::NAME => new IssueNameCombinator(),
            NameCombinator::NAME => new NameCombinator(),
            ReferenceCombinator::NAME => new ReferenceCombinator(),
            TypeCombinator::NAME => new TypeCombinator($typeParser),
            UriCombinator::NAME => new UriCombinator(),
            UrlCombinator::NAME => new UrlCombinator(),
            VariableCombinator::NAME => new VariableCombinator(),
            VersionCombinator::NAME => new VersionCombinator(),
        ];
    }
}
