<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

use TypeLang\PhpDoc\Build\Model\Captures;
use TypeLang\PhpDoc\Build\Model\LinkedModel;
use TypeLang\PhpDoc\Build\Model\Tag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;
use TypeLang\PhpDoc\Parser\Grammar\Rule\AlternationRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\LiteralRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\OptionalityRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RepetitionRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\SequencingRule;

/**
 * Generates the definition class of a tag: its canonical name, its grammar
 * as a {@see Spec} and a `create()` passing each capture to the constructor
 * parameter of the tag class it is bound to.
 */
final class DefinitionEmitter
{
    public function __construct(
        private readonly LinkedModel $model,
    ) {}

    public function emit(Tag $tag): string
    {
        $class = $tag->getDefinitionClass();
        $namespace = Imports::getNamespace($class);

        \assert($namespace !== '');

        $imports = new Imports($namespace);

        $body = 'final class ' . Imports::getShortName($class)
            . ' extends ' . $imports->add(TagDefinition::class) . "\n{\n"
            . $this->emitCanonicalName($tag) . "\n"
            . $this->emitConstructor($tag, $imports) . "\n"
            . $this->emitCreate($tag, $imports)
            . "}\n";

        return PhpFile::render($namespace, $imports, $this->getDocblock($tag), $tag->file, $body);
    }

    private function getDocblock(Tag $tag): string
    {
        $result = $tag->summary;

        if ($tag->description !== '') {
            $result .= "\n\n" . $tag->description;
        }

        return $result . "\n\n```\n" . self::renderGrammar($tag) . "\n```";
    }

    /**
     * The grammar the way {@see TagDefinition::__toString()} renders it.
     */
    public static function renderGrammar(Tag $tag): string
    {
        return \sprintf('"@%s" %s', $tag->name, $tag->grammar);
    }

    private function emitCanonicalName(Tag $tag): string
    {
        $type = 'non-empty-string';

        if (\strtolower($tag->name) === $tag->name) {
            $type = 'non-empty-lowercase-string';
        }

        return PhpFile::renderDocblock('@return ' . $type, 4)
            . "    public static function getCanonicalName(): string\n"
            . "    {\n"
            . '        return ' . PhpFile::renderString($tag->name) . ";\n"
            . "    }\n";
    }

    private function emitConstructor(Tag $tag, Imports $imports): string
    {
        $result = "    public function __construct()\n"
            . "    {\n"
            . "        parent::__construct(\n"
            . "            name: self::getCanonicalName(),\n"
            . '            spec: ' . $this->emitRule($tag->grammar, $imports, 12) . ",\n";

        if ($tag->placement !== TagPlacement::Any) {
            $result .= '            placement: ' . $imports->add(TagPlacement::class) . '::'
                . $tag->placement->name . ",\n";
        }

        return $result . "        );\n    }\n";
    }

    /**
     * @throws \LogicException in case of the rule kind is not supported
     */
    private function emitRule(RuleInterface $rule, Imports $imports, int $indent): string
    {
        $spec = $imports->add(Spec::class);

        return match (true) {
            $rule instanceof MatchRule => $spec . '::rule(' . $this->emitMatchArguments($rule, $imports) . ')',
            $rule instanceof LiteralRule => $spec . '::literal(' . PhpFile::renderString($rule->value)
                . $this->emitOptionalString($rule->alias) . ')',
            $rule instanceof SequencingRule => $this->emitCall($spec . '::sequence', $rule->rules, $imports, $indent),
            $rule instanceof AlternationRule => $this->emitCall($spec . '::oneOf', $rule->rules, $imports, $indent),
            $rule instanceof OptionalityRule => $this->emitCall($spec . '::maybe', [$rule->rule], $imports, $indent),
            $rule instanceof RepetitionRule => $this->emitCall(
                $spec . '::repeat',
                [$rule->rule],
                $imports,
                $indent,
                $rule->min > 0 ? (string) $rule->min : null,
            ),
            default => throw new \LogicException(\sprintf('Unsupported rule %s', $rule::class)),
        };
    }

    private function emitMatchArguments(MatchRule $rule, Imports $imports): string
    {
        $combinator = $this->model->combinators[$rule->rule] ?? null;

        \assert($combinator !== null);

        $result = $imports->add($combinator->class) . '::NAME';

        if ($rule->alias !== null) {
            $result .= ', ' . PhpFile::renderString($rule->alias);
        }

        if ($rule->renderAs !== null && $rule->alias === null) {
            return $result . ', renderAs: ' . PhpFile::renderString($rule->renderAs);
        }

        return $result . $this->emitOptionalString($rule->renderAs);
    }

    private function emitOptionalString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return ', ' . PhpFile::renderString($value);
    }

    /**
     * @param list<RuleInterface> $rules
     */
    private function emitCall(
        string $function,
        array $rules,
        Imports $imports,
        int $indent,
        ?string $extra = null,
    ): string {
        $pad = \str_repeat(' ', $indent + 4);
        $result = $function . "(\n";

        foreach ($rules as $rule) {
            $result .= $pad . $this->emitRule($rule, $imports, $indent + 4) . ",\n";
        }

        if ($extra !== null) {
            $result .= $pad . $extra . ",\n";
        }

        return $result . \str_repeat(' ', $indent) . ')';
    }

    private function emitCreate(Tag $tag, Imports $imports): string
    {
        $tagClass = $imports->add($tag->class);
        $captures = Captures::collect($tag->grammar);
        $class = $tag->extends ?? $tag->class;

        \assert(\class_exists($class));

        $constructor = (new \ReflectionClass($class))->getConstructor();

        \assert($constructor !== null);

        $byParameter = [];

        foreach ($captures->names as $capture) {
            $byParameter[$tag->getParameterName($capture)] = $capture;
        }

        $reads = '';
        $arguments = "            name: \$name,\n";

        foreach ($constructor->getParameters() as $parameter) {
            $capture = $byParameter[$parameter->name] ?? null;

            if ($capture === null) {
                continue;
            }

            if ($captures->isLiteral($capture)) {
                $reads .= '        $' . $parameter->name . ' = $result->has('
                    . PhpFile::renderString($capture) . ");\n\n";
                $arguments .= '            ' . $parameter->name . ': $' . $parameter->name . ",\n";

                continue;
            }

            $method = match (true) {
                $captures->isRepeated($capture) => 'getAll',
                $captures->isRequired($capture) => 'get',
                default => 'find',
            };

            $reads .= '        /** @var ' . $this->getParameterType($parameter, $tag, $imports) . " */\n"
                . '        $' . $parameter->name . ' = $result->' . $method . '('
                . PhpFile::renderString($capture) . ");\n\n";
            $arguments .= '            ' . $parameter->name . ': $' . $parameter->name . ",\n";
        }

        return '    public function create(string $name, ' . $imports->add(TagPayload::class) . ' $result): '
            . $tagClass . "\n"
            . "    {\n"
            . $reads
            . '        return new ' . $tagClass . "(\n"
            . $arguments
            . "        );\n"
            . "    }\n";
    }

    /**
     * The phpdoc type of the property of the same name (declared or inherited),
     * or the native type of the parameter, with the template parameters of
     * the base class replaced by the arguments of the tag.
     */
    private function getParameterType(\ReflectionParameter $parameter, Tag $tag, Imports $imports): string
    {
        $class = $parameter->getDeclaringClass();

        \assert($class !== null);

        if ($class->hasProperty($parameter->name)) {
            $property = $class->getProperty($parameter->name);
            $owner = $property->getDeclaringClass();
            $comment = (string) $property->getDocComment();

            // The type of a generic base is known only from the template
            // arguments of the tag
            $isResolvable = $tag->templateArguments !== []
                || !\str_contains((string) $owner->getDocComment(), '@template');

            if ($isResolvable && \preg_match('/@var\s+(\S(?:.*\S)?)\s*(?:\*\/|\n)/', $comment, $matches) === 1) {
                return $this->replaceTemplates($matches[1], $owner, $tag, $imports);
            }
        }

        $type = $parameter->getType();

        \assert($type !== null);

        return $this->renderNativeType($type, $imports);
    }

    private function renderNativeType(\ReflectionType $type, Imports $imports): string
    {
        if ($type instanceof \ReflectionUnionType) {
            return \implode('|', \array_map(
                fn(\ReflectionType $inner): string => $this->renderNativeType($inner, $imports),
                $type->getTypes(),
            ));
        }

        \assert($type instanceof \ReflectionNamedType);

        $name = $type->getName();

        if (!$type->isBuiltin()) {
            \assert($name !== '');

            $name = $imports->add($name);
        }

        if ($type->allowsNull() && $name !== 'null' && $name !== 'mixed') {
            return $name . '|null';
        }

        return $name;
    }

    /**
     * @param \ReflectionClass<object> $declaring
     * @throws \LogicException in case of the tag gives no argument for
     *        a template parameter
     */
    private function replaceTemplates(string $type, \ReflectionClass $declaring, Tag $tag, Imports $imports): string
    {
        \preg_match_all('/@template(?:-covariant)?\s+(\w+)/', (string) $declaring->getDocComment(), $matches);

        foreach ($matches[1] as $index => $template) {
            $argument = $tag->templateArguments[$index] ?? null;

            if ($argument === null) {
                throw new \LogicException(\sprintf('Tag @%s gives no argument for %s', $tag->name, $template));
            }

            $type = (string) \preg_replace('/\b' . $template . '\b/', $imports->add($argument), $type);
        }

        return $type;
    }
}
