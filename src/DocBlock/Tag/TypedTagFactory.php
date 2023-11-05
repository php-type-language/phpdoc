<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface;
use TypeLang\Parser\Traverser;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\Visitor\NodeCompleteOffsetVisitor;

/**
 * @template TReturn of TagInterface
 *
 * @template-extends TagFactory<TReturn>
 */
abstract class TypedTagFactory extends TagFactory
{
    private static ?NamedTypeNode $mixed = null;

    public function __construct(
        protected readonly ParserInterface $parser,
        DescriptionFactoryInterface $descriptions,
    ) {
        parent::__construct($descriptions);
    }

    /**
     * @return array{TypeStatement, non-empty-string|null}
     */
    protected function extractType(string $body): array
    {
        try {
            $type = $this->parser->parse($body);

            if ($type instanceof TypeStatement) {
                return [$type, $this->slice($type, $body)];
            }

            return [$this->createMixedType(), $body ?: null];
        } catch (\Throwable) {
            return [$this->createMixedType(), $body ?: null];
        }
    }

    private function createMixedType(): NamedTypeNode
    {
        return self::$mixed ??= new NamedTypeNode(
            name: new Name('mixed'),
        );
    }

    /**
     * @return non-empty-string|null
     */
    private function slice(TypeStatement $type, string $body): ?string
    {
        $offset = $this->getMaxOffset($type);

        return \ltrim(\substr($body, $offset)) ?: null;
    }

    /**
     * @return int<0, max>
     */
    private function getMaxOffset(TypeStatement $stmt): int
    {
        $visitor = Traverser::through(new NodeCompleteOffsetVisitor(), [$stmt]);

        return $visitor->offset;
    }
}
