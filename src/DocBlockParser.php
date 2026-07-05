<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\AccessCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\AuthorNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\CallableTypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\EmailCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\AccessTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ApiTag\ApiTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AuthorTag\AuthorTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CategoryTag\CategoryTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CopyrightTag\CopyrightTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag\DeprecatedTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ExampleTag\ExampleTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FilesourceTag\FilesourceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FinalTag\FinalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\GlobalTag\GlobalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\IgnoreTag\IgnoreTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\UseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritDocTag\InheritDocTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\LicenseTag\LicenseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\LinkTag\LinkTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NameTag\NameTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NoNamedArgumentsTag\NoNamedArgumentsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\OverrideTag\OverrideTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PackageTag\PackageTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamClosureThisTag\ParamClosureThisTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag\ParamOutTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag\PureUnlessCallableIsImpureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag\SealMethodsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag\SealPropertiesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SeeTag\SeeTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SinceTag\SinceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SourceTag\SourceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\StaticTag\StaticTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag\StaticVarTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SubpackageTag\SubpackageTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TodoTag\TodoTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VersionTag\VersionTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Exception\ParsingException;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;
use TypeLang\PhpDoc\Exception\TagParsingException;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\DocBlockAnalyzer;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;
use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;
use TypeLang\PhpDoc\Parser\Splitter\StringSplitter;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\Parser\Tag\TagParserInterface;

/**
 * @phpstan-import-type CombinatorType from Grammar
 */
final readonly class DocBlockParser implements DocBlockParserInterface
{
    public TagFactoryInterface $tags;

    private DocBlockAnalyzer $docBlockAnalyzer;
    private TagParserInterface $tagParser;
    private DescriptionParserInterface $descriptionParser;

    public function __construct()
    {
        $this->docBlockAnalyzer = $this->createAnalyzer(
            splitter: $this->createDocBlockSplitter(),
        );

        $this->tags = $this->createTagFactory();

        $this->tagParser = $this->createTagParser($this->tags);
        $this->descriptionParser = $this->createDescriptionParser($this->tagParser);
    }

    private function createAnalyzer(SplitterInterface $splitter): DocBlockAnalyzer
    {
        return new DocBlockAnalyzer($splitter);
    }

    private function createDocBlockSplitter(): SplitterInterface
    {
        return new StringSplitter();
    }

    /**
     * @return array<non-empty-string, CombinatorType>
     */
    private function createDefaultCombinators(): array
    {
        $typeParser = new TypeParser();
        $types = new TypeCombinator($typeParser);

        return [
            AccessCombinator::NAME => new AccessCombinator(),
            UriCombinator::NAME => new UriCombinator(),
            UrlCombinator::NAME => new UrlCombinator(),
            ReferenceCombinator::NAME => new ReferenceCombinator(),
            TypeCombinator::NAME => $types,
            CallableTypeCombinator::NAME => new CallableTypeCombinator($types),
            VariableCombinator::NAME => new VariableCombinator(),
            AuthorNameCombinator::NAME => new AuthorNameCombinator(),
            EmailCombinator::NAME => new EmailCombinator(),
            IntegerCombinator::NAME => new IntegerCombinator(),
            IssueNameCombinator::NAME => new IssueNameCombinator(),
            VersionCombinator::NAME => new VersionCombinator(),
            NameCombinator::NAME => new NameCombinator(),
            DescriptionCombinator::NAME => new \ReflectionClass(DescriptionCombinator::class)
                ->newLazyProxy(fn(): DescriptionCombinator => new DescriptionCombinator(
                    descriptionParser: $this->descriptionParser,
                )),
        ];
    }

    /**
     * @return array<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    private function createDefaultTagAliases(): array
    {
        return [
            'inherits' => ExtendsTagDefinition::NAME,
            'template-extends' => ExtendsTagDefinition::NAME,
            'template-implements' => ImplementsTagDefinition::NAME,
            'template-use' => UseTagDefinition::NAME,
            'template-invariant' => TemplateTagDefinition::NAME,
            // A fairly common typos in code
            'returns' => ReturnTagDefinition::NAME,
            'throw' => ThrowsTagDefinition::NAME,
        ];
    }

    /**
     * @return array<non-empty-lowercase-string, TagDefinitionInterface>
     */
    private function createDefaultTagDefinitions(): array
    {
        return [
            LinkTagDefinition::NAME => new LinkTagDefinition(),
            SeeTagDefinition::NAME => new SeeTagDefinition(),
            ReturnTagDefinition::NAME => new ReturnTagDefinition(),
            ThrowsTagDefinition::NAME => new ThrowsTagDefinition(),
            MixinTagDefinition::NAME => new MixinTagDefinition(),
            ExtendsTagDefinition::NAME => new ExtendsTagDefinition(),
            ImplementsTagDefinition::NAME => new ImplementsTagDefinition(),
            UseTagDefinition::NAME => new UseTagDefinition(),
            RequireExtendsTagDefinition::NAME => new RequireExtendsTagDefinition(),
            RequireImplementsTagDefinition::NAME => new RequireImplementsTagDefinition(),
            ParamTagDefinition::NAME => new ParamTagDefinition(),
            ParamOutTagDefinition::NAME => new ParamOutTagDefinition(),
            ParamClosureThisTagDefinition::NAME => new ParamClosureThisTagDefinition(),
            PropertyTagDefinition::NAME => new PropertyTagDefinition(),
            PropertyReadTagDefinition::NAME => new PropertyReadTagDefinition(),
            PropertyWriteTagDefinition::NAME => new PropertyWriteTagDefinition(),
            ParamImmediatelyInvokedCallableTagDefinition::NAME => new ParamImmediatelyInvokedCallableTagDefinition(),
            ParamLaterInvokedCallableTagDefinition::NAME => new ParamLaterInvokedCallableTagDefinition(),
            UnusedParamTagDefinition::NAME => new UnusedParamTagDefinition(),
            AbstractTagDefinition::NAME => new AbstractTagDefinition(),
            ApiTagDefinition::NAME => new ApiTagDefinition(),
            FinalTagDefinition::NAME => new FinalTagDefinition(),
            FilesourceTagDefinition::NAME => new FilesourceTagDefinition(),
            IgnoreTagDefinition::NAME => new IgnoreTagDefinition(),
            InheritDocTagDefinition::NAME => new InheritDocTagDefinition(),
            InternalTagDefinition::NAME => new InternalTagDefinition(),
            NoNamedArgumentsTagDefinition::NAME => new NoNamedArgumentsTagDefinition(),
            OverrideTagDefinition::NAME => new OverrideTagDefinition(),
            ReadonlyTagDefinition::NAME => new ReadonlyTagDefinition(),
            SealMethodsTagDefinition::NAME => new SealMethodsTagDefinition(),
            SealPropertiesTagDefinition::NAME => new SealPropertiesTagDefinition(),
            PureUnlessCallableIsImpureTagDefinition::NAME => new PureUnlessCallableIsImpureTagDefinition(),
            TodoTagDefinition::NAME => new TodoTagDefinition(),
            CategoryTagDefinition::NAME => new CategoryTagDefinition(),
            CopyrightTagDefinition::NAME => new CopyrightTagDefinition(),
            PackageTagDefinition::NAME => new PackageTagDefinition(),
            SubpackageTagDefinition::NAME => new SubpackageTagDefinition(),
            UsesTagDefinition::NAME => new UsesTagDefinition(),
            UsedByTagDefinition::NAME => new UsedByTagDefinition(),
            AuthorTagDefinition::NAME => new AuthorTagDefinition(),
            VersionTagDefinition::NAME => new VersionTagDefinition(),
            TemplateTagDefinition::NAME => new TemplateTagDefinition(),
            TemplateCovariantTagDefinition::NAME => new TemplateCovariantTagDefinition(),
            TemplateContravariantTagDefinition::NAME => new TemplateContravariantTagDefinition(),
            SinceTagDefinition::NAME => new SinceTagDefinition(),
            DeprecatedTagDefinition::NAME => new DeprecatedTagDefinition(),
            LicenseTagDefinition::NAME => new LicenseTagDefinition(),
            VarTagDefinition::NAME => new VarTagDefinition(),
            GlobalTagDefinition::NAME => new GlobalTagDefinition(),
            SuppressTagDefinition::NAME => new SuppressTagDefinition(),
            SourceTagDefinition::NAME => new SourceTagDefinition(),
            MethodTagDefinition::NAME => new MethodTagDefinition(),
            ExampleTagDefinition::NAME => new ExampleTagDefinition(),
            AccessTagDefinition::NAME => new AccessTagDefinition(),
            NameTagDefinition::NAME => new NameTagDefinition(),
            StaticTagDefinition::NAME => new StaticTagDefinition(),
            StaticVarTagDefinition::NAME => new StaticVarTagDefinition(),
        ];
    }

    private function createTagFactory(): TagFactoryInterface
    {
        return new TagFactory(
            definitions: $this->createDefaultTagDefinitions(),
            aliases: $this->createDefaultTagAliases(),
            combinators: $this->createDefaultCombinators(),
        );
    }

    private function createTagParser(TagFactoryInterface $factory): TagParserInterface
    {
        return new StringTagParser($factory);
    }

    private function createDescriptionParser(TagParserInterface $parser): DescriptionParserInterface
    {
        return new BalancedBraceAwareParser($parser);
    }

    /**
     * @param list<Segment> $segments
     * @return list<TagInterface>
     * @throws PhpDocExceptionInterface
     */
    private function createTags(array $segments, string $docblock): array
    {
        $result = [];

        foreach ($segments as $segment) {
            try {
                $result[] = $this->tagParser->parse($segment->text);
            } catch (\Throwable $e) {
                throw $this->failure($e, $docblock, $segment->offset);
            }
        }

        return $result;
    }

    /**
     * @throws PhpDocExceptionInterface
     */
    private function tryCreateDescription(Segment $segment, string $docblock): ?DescriptionInterface
    {
        try {
            return $this->descriptionParser->tryParse($segment->text);
        } catch (\Throwable $e) {
            throw $this->failure($e, $docblock, $segment->offset);
        }
    }

    /**
     * Guarantees that only a {@see PhpDocExceptionInterface} leaves the parser.
     *
     * A parsing error is rebased onto the full docblock so that its offset
     * becomes absolute (e.g. the position of the tag that failed to parse). Any
     * other, internal failure is wrapped as a {@see TagParsingException} at the
     * same location.
     *
     * @param int<0, max> $offset byte offset of the failing section inside $source
     */
    private function failure(\Throwable $e, string $source, int $offset): PhpDocExceptionInterface
    {
        // A parsing error carries an offset relative to the failing section;
        // rebasing it onto $source turns that into an absolute location.
        if ($e instanceof ParsingException) {
            return $e->withSource($source, $e->offset + $offset);
        }

        // Any other library error is already contractual and passes through.
        if ($e instanceof PhpDocExceptionInterface) {
            return $e;
        }

        // An internal (non-library) failure is wrapped so that the whole
        // docblock and the failing offset are still reported to the caller.
        return TagParsingException::becauseInternalErrorOccurs($e, $source, $offset);
    }

    public function parse(#[Language('InjectablePHP')] string $docblock): DocBlock
    {
        $data = $this->docBlockAnalyzer->analyze($docblock);

        return new DocBlock(
            description: $this->tryCreateDescription($data->description, $docblock),
            tags: $this->createTags($data->tags, $docblock),
        );
    }
}
