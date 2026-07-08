<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Tag\FlowType;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a taint flow kind, that is one of the {@see FlowType} keywords.
 *
 * @template-implements CombinatorInterface<FlowType>
 */
final readonly class FlowTypeCombinator implements CombinatorInterface
{
    public const string NAME = 'FlowType';

    public function __invoke(Cursor $cursor): FlowType
    {
        $flow = FlowType::tryFrom($cursor->readPhpIdentifier());

        if ($flow === null) {
            throw new NoMatchException('Expected a flow type (one of "TaintSource", "TaintSink", "TaintSpecialize" or "TaintUnescape")');
        }

        return $flow;
    }
}
