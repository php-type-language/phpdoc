<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PhpDoc\Build\Model\Alias;
use TypeLang\PhpDoc\Build\Model\Combinator;
use TypeLang\PhpDoc\Build\Model\Model;
use TypeLang\PhpDoc\Build\Model\ModelException;
use TypeLang\PhpDoc\Build\Model\ModelLinker;
use TypeLang\PhpDoc\Build\Model\Package;
use TypeLang\PhpDoc\Build\Model\Tag;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class ModelLinkerTest extends TestCase
{
    public function testAliasInPackageOfTargetIsRuntimeAlias(): void
    {
        $linked = (new ModelLinker())->link(self::model(
            tags: [self::tag('flag', 'one')],
            aliases: [new Alias('flag-alias', 'flag', 'one', [], 'test.xml')],
        ));

        self::assertSame(['flag-alias' => 'flag'], $linked->packages['one']->aliases);
        self::assertSame(['flag'], \array_keys($linked->packages['one']->tags));
    }

    public function testAliasInOtherPackageGetsDefinitionOfTarget(): void
    {
        $tag = self::tag('flag', 'one');

        $linked = (new ModelLinker())->link(self::model(
            tags: [$tag],
            aliases: [new Alias('two-flag', 'flag', 'two', [], 'test.xml')],
        ));

        self::assertSame([], $linked->packages['two']->aliases);
        self::assertSame(['two-flag' => $tag], $linked->packages['two']->tags);
    }

    public function testAliasChainResolvesToTag(): void
    {
        $tag = self::tag('flag');

        $linked = (new ModelLinker())->link(self::model(
            tags: [$tag],
            aliases: [
                new Alias('two-flag', 'flag', 'two', [], 'test.xml'),
                new Alias('two-other', 'two-flag', 'two', [], 'test.xml'),
            ],
        ));

        self::assertSame(['two-flag' => $tag], $linked->packages['two']->tags);
        self::assertSame(['two-other' => 'two-flag'], $linked->packages['two']->aliases);
    }

    /**
     * @param non-empty-string $error
     */
    #[DataProvider('invalidModelDataProvider')]
    public function testInvalidModelIsRejected(Model $model, string $error): void
    {
        try {
            (new ModelLinker())->link($model);
        } catch (ModelException $e) {
            self::assertStringContainsString($error, $e->getMessage());

            return;
        }

        self::fail('The model must be rejected');
    }

    /**
     * @return iterable<non-empty-string, array{Model, non-empty-string}>
     */
    public static function invalidModelDataProvider(): iterable
    {
        yield 'unknown combinator' => [
            self::model(tags: [self::tag('flag', 'one', Spec::rule('Missing'))]),
            'unknown combinator <Missing>',
        ];

        yield 'combinator of another package' => [
            self::model(
                tags: [self::tag('flag', 'one', Spec::rule('Own'))],
                combinators: [new Combinator('Own', VariableCombinator::class, 'two', true, 'test.xml')],
            ),
            'combinator <Own> of "two" is not available in "one"',
        ];

        yield 'alias to nothing' => [
            self::model(
                tags: [self::tag('flag', 'one')],
                aliases: [new Alias('broken', 'missing', 'one', [], 'test.xml')],
            ),
            'alias @broken: @missing is neither a tag nor an alias',
        ];

        yield 'recursive alias' => [
            self::model(
                tags: [self::tag('flag', 'one')],
                aliases: [
                    new Alias('a', 'b', 'one', [], 'test.xml'),
                    new Alias('b', 'a', 'one', [], 'test.xml'),
                ],
            ),
            'is recursive',
        ];

        yield 'tag declared twice' => [
            self::model(tags: [self::tag('flag', 'one'), self::tag('FLAG', 'two', class: 'Other')]),
            'tag @FLAG is declared twice',
        ];

        yield 'name registered twice' => [
            self::model(
                tags: [self::tag('flag', 'one'), self::tag('other', 'one', class: 'Other')],
                aliases: [new Alias('Flag', 'other', 'one', [], 'test.xml')],
            ),
            'name @Flag is registered twice in "one"',
        ];

        yield 'tag registered nowhere' => [
            self::model(tags: [self::tag('flag')]),
            'tag @flag is registered by no package',
        ];

        yield 'capture without parameter' => [
            self::model(tags: [self::tag('flag', 'one', Spec::rule('Variable', 'variable'))]),
            'capture "variable" has no parameter $variable',
        ];

        yield 'required parameter not captured' => [
            self::model(tags: [self::tag('typed', 'one', Spec::rule('Variable'), TypedTag::class)]),
            'required parameter $statement is captured by nothing',
        ];

        yield 'optional capture to non-nullable parameter' => [
            self::model(tags: [self::tag(
                'typed',
                'one',
                Spec::maybe(Spec::rule('Type', 'type')),
                TypedTag::class,
                ['type' => 'statement'],
            )]),
            'optional capture "type" goes to non-nullable $statement',
        ];

        yield 'name registered by two packages' => [
            self::model(
                tags: [self::tag('flag', 'one')],
                aliases: [
                    new Alias('shared', 'flag', 'one', [], 'test.xml'),
                    new Alias('shared', 'flag', 'two', [], 'test.xml'),
                ],
            ),
            '@shared is registered by "one", "two"',
        ];

        yield 'combinator of a package needing the type parser' => [
            self::model(
                tags: [self::tag('flag', 'one')],
                combinators: [new Combinator('Own', TypeCombinator::class, 'one', true, 'test.xml')],
            ),
            'combinator <Own>: the generated code cannot pass $typeParser to the constructor',
        ];

        yield 'manual combinator of a package' => [
            self::model(
                tags: [self::tag('flag', 'one')],
                combinators: [new Combinator('Own', VariableCombinator::class, 'one', false, 'test.xml')],
            ),
            'combinator <Own>: only a combinator of the parser is created manually',
        ];

        yield 'literal capture to non-bool parameter' => [
            self::model(tags: [self::tag(
                'param',
                'one',
                self::paramGrammar(),
                ParamTag::class,
                ['type' => 'statement', 'variadic' => 'description'],
            )]),
            'literal capture "variadic" goes to non-bool $description',
        ];
    }

    public function testLiteralCaptureGoesToBoolParameter(): void
    {
        $linked = (new ModelLinker())->link(self::model(tags: [self::tag(
            'param',
            'one',
            self::paramGrammar(),
            ParamTag::class,
            ['type' => 'statement', 'variadic' => 'isVariadic'],
        )]));

        self::assertSame(['param'], \array_keys($linked->packages['one']->tags));
    }

    private static function paramGrammar(): RuleInterface
    {
        return Spec::sequence(
            Spec::rule('Type', 'type'),
            Spec::maybe(Spec::literal('...', 'variadic')),
            Spec::rule('Variable', 'variable'),
        );
    }

    /**
     * @param list<Tag> $tags
     * @param list<Alias> $aliases
     * @param list<Combinator> $combinators
     */
    private static function model(array $tags = [], array $aliases = [], array $combinators = []): Model
    {
        return new Model(
            packages: [
                new Package('one', 'OnePlatform', 'One', '', 'test.xml'),
                new Package('two', 'TwoPlatform', 'Two', '', 'test.xml'),
            ],
            combinators: [
                new Combinator('Description', DescriptionCombinator::class, null, false, 'test.xml'),
                new Combinator('Type', TypeCombinator::class, null, true, 'test.xml'),
                new Combinator('Variable', VariableCombinator::class, null, true, 'test.xml'),
                ...$combinators,
            ],
            tags: $tags,
            aliases: $aliases,
        );
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $package
     * @param non-empty-string $extends
     * @param array<non-empty-string, non-empty-string> $parameters
     * @param non-empty-string $class
     */
    private static function tag(
        string $name,
        ?string $package = null,
        ?RuleInterface $grammar = null,
        string $extends = FlagTag::class,
        array $parameters = [],
        string $class = 'Flag',
    ): Tag {
        return new Tag(
            name: $name,
            class: 'Test\\' . $class . 'Tag',
            extends: $extends,
            templateArguments: [],
            isDefinitionGenerated: true,
            placement: TagPlacement::Any,
            package: $package,
            summary: 'A tag of the test.',
            description: '',
            grammar: $grammar ?? Spec::maybe(Spec::rule('Description', 'description')),
            parameters: $parameters,
            examples: [],
            since: null,
            deprecated: null,
            file: 'test.xml',
        );
    }
}
