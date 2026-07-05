<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads the trailing description, consuming everything that is left and
 * delegating the parsing (including any inline tags) to the description parser.
 *
 * There is nothing to read when only whitespace is left, so wrap it in
 * {@see OptionalityRule} to make the description optional.
 *
 * @implements CombinatorInterface<DescriptionInterface>
 */
final readonly class DescriptionCombinator implements CombinatorInterface
{
    public const string NAME = 'Description';

    public function __construct(
        private DescriptionParserInterface $descriptionParser,
    ) {}

    public function __invoke(Cursor $cursor): DescriptionInterface
    {
        $text = \rtrim($cursor->readRemainder());

        if ($text === '') {
            throw new NoMatchException('Expected a description');
        }

        return $this->descriptionParser->parse($text);
    }
}
