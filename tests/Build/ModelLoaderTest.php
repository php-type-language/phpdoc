<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use TypeLang\PhpDoc\Build\Model\ModelException;
use TypeLang\PhpDoc\Build\Model\ModelLoader;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\Tests\TestCase;

final class ModelLoaderTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = \sys_get_temp_dir() . '/phpdoc-model-' . \bin2hex(\random_bytes(4));

        \mkdir($this->directory);
        \copy(ModelTest::MODEL_DIRECTORY . '/phpdoc.xsd', $this->directory . '/phpdoc.xsd');
    }

    protected function tearDown(): void
    {
        foreach (\glob($this->directory . '/*') ?: [] as $file) {
            \unlink($file);
        }

        \rmdir($this->directory);
    }

    public function testTagIsRead(): void
    {
        $this->write(<<<'XML'
            <model package="one">
                <tag name="param"
                     class="TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag"
                     extends="TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag"
                     template="TypeLang\PhpDoc\DocBlock\Reference\CodeReference"
                     placement="block">
                    <summary>
                        The `@param` tag
                        documents an argument.
                    </summary>
                    <grammar>
                        <sequence>
                            <rule name="Type" as="type" parameter="statement"/>
                            <maybe><rule name="Variable" as="variable" shown-as="Name"/></maybe>
                        </sequence>
                    </grammar>
                    <example title="Plain.">
                        /**
                         * @param int $a
                         */
                    </example>
                </tag>
            </model>
            XML);

        $tag = (new ModelLoader())->load($this->directory)->tags[0];

        self::assertSame('TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag', $tag->class);
        self::assertSame('TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag', $tag->extends);
        self::assertSame(['TypeLang\PhpDoc\DocBlock\Reference\CodeReference'], $tag->templateArguments);
        self::assertSame('one', $tag->package);
        self::assertSame("The `@param` tag\ndocuments an argument.", $tag->summary);
        self::assertSame(['type' => 'statement'], $tag->parameters);
        self::assertSame('statement', $tag->getParameterName('type'));
        self::assertSame('returnType', $tag->getParameterName('return-type'));
        self::assertSame("/**\n * @param int \$a\n */", $tag->examples[0]->code);
        self::assertEquals(
            Spec::sequence(
                Spec::rule('Type', 'type'),
                Spec::maybe(Spec::rule('Variable', 'variable', 'Name')),
            ),
            $tag->grammar,
        );
    }

    public function testFileViolatingSchemaIsRejected(): void
    {
        $this->write('<model><tag name="x"/></model>');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("one.xml: line 2: Element 'tag': The attribute 'class' is required");

        (new ModelLoader())->load($this->directory);
    }

    public function testRelativeClassNameIsRejected(): void
    {
        $this->write(<<<'XML'
            <model>
                <combinator name="Type" class="TypeCombinator"/>
            </model>
            XML);

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage("attribute 'class': [facet 'pattern']");

        (new ModelLoader())->load($this->directory);
    }

    private function write(string $xml): void
    {
        \file_put_contents($this->directory . '/one.xml', '<?xml version="1.0"?>' . "\n" . $xml);
    }
}
