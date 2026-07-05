<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;

/**
 * Groups the significant segments of a DocBlock comment into sections (a
 * description followed by tags) and builds the {@see SourceMap} for them.
 *
 * It only slices and maps the comment: parsing the description and tag
 * contents is left to the caller.
 */
final readonly class DocBlockAnalyzer
{
    public function __construct(
        private SplitterInterface $splitter,
    ) {}

    public function analyze(string $docblock): RawDocBlock
    {
        $buffer = '';
        $currentOffset = 0;
        $hasOffset = false;

        /** @var list<Segment> $computedSegments */
        $computedSegments = [];

        foreach ($this->splitter->split($docblock) as $segment) {
            $segmentText = $segment->text;

            if ($segmentText[0] === '@') {
                $computedSegments[] = new Segment($buffer, $currentOffset);
                $buffer = '';
                $currentOffset = $segment->offset;
                $hasOffset = true;
            } elseif (!$hasOffset) {
                // Anchor the leading description at its first significant line.
                $currentOffset = $segment->offset;
                $hasOffset = true;
            }

            $buffer .= $segment->text;
        }

        $computedSegments[] = new Segment($buffer, $currentOffset);

        return new RawDocBlock(
            description: \array_shift($computedSegments),
            tags: $computedSegments,
        );
    }
}
