<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;

/**
 * Groups the significant segments of a DocBlock comment into a leading
 * description followed by tags, joining each tag with its continuation lines.
 *
 * A line opening with "@" starts a new tag. Any line before the first tag
 * belongs to the description, and any non-tag line after a tag continues it.
 */
final class DocBlockAnalyzer
{
    public function __construct(
        private readonly SplitterInterface $splitter,
    ) {}

    public function analyze(string $docblock): RawDocBlock
    {
        // @phpstan-ignore-next-line empty prototype, text set before clone
        $prototype = new Segment('');

        /** @var list<Segment> */
        $groups = [];

        $buffer = '';
        $offset = 0;

        foreach ($this->splitter->split($docblock) as $segment) {
            // A tag opens a new group and finishes the previous one.
            if ($buffer !== '' && $segment->text[0] === '@') {
                // @phpstan-ignore-next-line writes the read-only prototype
                $prototype->text = $buffer;
                // @phpstan-ignore-next-line writes the read-only prototype
                $prototype->offset = $offset;

                $groups[] = clone $prototype;
                $buffer = '';
            }

            if ($buffer === '') {
                $offset = $segment->offset;
            }

            $buffer .= $segment->text;
        }

        if ($buffer !== '') {
            // @phpstan-ignore-next-line writes the read-only prototype
            $prototype->text = $buffer;
            // @phpstan-ignore-next-line writes the read-only prototype
            $prototype->offset = $offset;

            $groups[] = clone $prototype;
        }

        // The leading group is the description unless it already is a tag.
        $description = null;

        if ($groups !== [] && $groups[0]->text[0] !== '@') {
            $description = \array_shift($groups);
        }

        return new RawDocBlock($description, $groups);
    }
}
