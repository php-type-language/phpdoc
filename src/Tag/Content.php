<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Content\IdentifierApplicator;
use TypeLang\PHPDoc\Tag\Content\OptionalIdentifierApplicator;
use TypeLang\PHPDoc\Tag\Content\OptionalTypeParserApplicator;
use TypeLang\PHPDoc\Tag\Content\OptionalValueApplicator;
use TypeLang\PHPDoc\Tag\Content\OptionalVariableNameApplicator;
use TypeLang\PHPDoc\Tag\Content\TypeParserApplicator;
use TypeLang\PHPDoc\Tag\Content\ValueApplicator;
use TypeLang\PHPDoc\Tag\Content\VariableNameApplicator;
use TypeLang\PHPDoc\Tag\Description\DescriptionInterface;

class Content implements \Stringable
{
    public readonly string $source;

    /**
     * @var int<0, max>
     *
     * @psalm-readonly-allow-private-mutation
     */
    public int $offset = 0;

    public function __construct(
        public string $value,
    ) {
        $this->source = $this->value;
    }

    /**
     * @param int<0, max> $offset
     */
    public function shift(int $offset, bool $ltrim = true): void
    {
        if ($offset <= 0) {
            return;
        }

        $size = \strlen($this->value);
        $this->value = \substr($this->value, $offset);

        if ($ltrim) {
            $this->value = \ltrim($this->value);
        }

        // @phpstan-ignore-next-line : Offset already greater than 0
        $this->offset += $size - \strlen($this->value);
    }

    /**
     * @param callable(self):bool $context
     */
    public function transactional(callable $context): void
    {
        $offset = $this->offset;
        $value = $this->value;

        if ($context($this) === false) {
            $this->offset = $offset;
            $this->value = $value;
        }
    }

    public function getTagException(string $message, ?\Throwable $previous = null): InvalidTagException
    {
        return new InvalidTagException(
            source: $this->source,
            offset: $this->offset,
            message: $message,
            previous: $previous,
        );
    }

    /**
     * @param non-empty-string $tag
     *
     * @api
     */
    public function nextType(string $tag, TypesParserInterface $parser): TypeStatement
    {
        return $this->apply(new TypeParserApplicator($tag, $parser));
    }

    /**
     * @api
     */
    public function nextOptionalType(TypesParserInterface $parser): ?TypeStatement
    {
        return $this->apply(new OptionalTypeParserApplicator($parser));
    }

    /**
     * @param non-empty-string $tag
     *
     * @return non-empty-string
     * @api
     */
    public function nextIdentifier(string $tag): string
    {
        return $this->apply(new IdentifierApplicator($tag));
    }

    /**
     * @return non-empty-string|null
     * @api
     */
    public function nextOptionalIdentifier(): ?string
    {
        return $this->apply(new OptionalIdentifierApplicator());
    }

    /**
     * @param non-empty-string $tag
     *
     * @return non-empty-string
     * @api
     */
    public function nextVariable(string $tag): string
    {
        return $this->apply(new VariableNameApplicator($tag));
    }

    /**
     * @return non-empty-string|null
     * @api
     */
    public function nextOptionalVariable(): ?string
    {
        return $this->apply(new OptionalVariableNameApplicator());
    }

    /**
     * @template T of non-empty-string
     *
     * @param non-empty-string $tag
     * @param T $value
     *
     * @return T
     * @api
     */
    public function nextValue(string $tag, string $value): string
    {
        /** @var T */
        return $this->apply(new ValueApplicator($tag, $value));
    }

    /**
     * @template T of non-empty-string
     *
     * @param T $value
     *
     * @return T|null
     * @api
     */
    public function nextOptionalValue(string $value): ?string
    {
        /** @var T|null */
        return $this->apply(new OptionalValueApplicator($value));
    }

    /**
     * @template T of mixed
     *
     * @param callable(Content):T $applicator
     *
     * @return T
     */
    public function apply(callable $applicator): mixed
    {
        return $applicator($this);
    }

    public function toDescription(DescriptionParserInterface $descriptions): DescriptionInterface
    {
        return $descriptions->parse($this->value);
    }

    public function toOptionalDescription(DescriptionParserInterface $descriptions): ?DescriptionInterface
    {
        if (\trim($this->value) === '') {
            return null;
        }

        return $descriptions->parse(\rtrim($this->value));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
