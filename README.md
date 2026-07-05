<a href="https://github.com/php-type-language" target="_blank">
    <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
</a>

<p align="center">
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/require/php?style=for-the-badge" alt="PHP 8.4+"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/phpdoc/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/phpdoc/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/phpdoc/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/php-type-language/phpdoc/tests.yml?label=Tests&style=flat-square&logo=unpkg"></a>
</p>

---

The reference implementation of the [TypeLang](https://typelang.dev) PHPDoc
parser. It turns a raw `/** ... */` comment into an immutable, iterable object
graph of its description and tags.

> Full documentation is available at [typelang.dev](https://typelang.dev).

## Installation

TypeLang PhpDoc Parser is available as a Composer package and can be installed
using the following command in the root of your project:

```sh
composer require type-lang/phpdoc
```

## Quick Start

```php
use TypeLang\PhpDoc\DocBlockParser;

$parser = new DocBlockParser();

$block = $parser->parse(<<<'PHPDOC'
    /**
     * Sends a notification to the given recipient.
     *
     * @see Mailer::send() The underlying transport.
     * @link https://example.com/docs Delivery documentation.
     * @return bool
     */
    PHPDOC);

// The leading description (everything before the first tag)
echo $block->description;
// "Sends a notification to the given recipient."

// Tags form an ordered, countable, iterable collection
echo \count($block); // 3

foreach ($block as $tag) {
    echo $tag->name; // "see", "link", "return"
}

// Known tags expose their parsed parts
$see = $block[0]; // SeeTag
echo $see->reference; // "Mailer::send()"
```

## Structural Elements

### DocBlock

A `DocBlock` is the representation of a whole comment: its description and its
tags.

```
/**                                 |
 * Hello world                      | ← DocBlock's description.
 *                                  |
 * @param int $example              | ← DocBlock's tag #1.
 * @throws \Throwable Description   | ← DocBlock's tag #2.
 */                                 |
```

The object is immutable and additionally behaves as a collection of its tags,
so it can be counted, iterated and accessed by offset:

```php
/**
 * DocBlock structure pseudocode (real impl may differ)
 *
 * @template-implements IteratorAggregate<array-key, TagInterface>
 * @template-implements ArrayAccess<array-key, TagInterface>
 */
final readonly class DocBlock implements
    IteratorAggregate,
    ArrayAccess,
    Countable
{
    public ?DescriptionInterface $description;

    /** @var list<TagInterface> */
    public array $tags;
}
```

### Description

A description is a `DescriptionInterface` implemented by one of two objects:

```
   DescriptionInterface
             │
     ┌───────┴───────┐
     │               │
Description   TaggedDescription
```

A plain `Description` is just text:

```
/**                  |
 * Hello world       | ← This is a simple description
   ↑↑↑↑↑↑↑↑↑↑↑       |
 */
```

```php
/**
 * Simple description structure pseudocode (real impl may differ)
 */
final readonly class Description implements DescriptionInterface
{
    public string $value;
}
```

A description may also contain **inline tags**. It is then represented as a
`TaggedDescription`, a composite of text fragments and nested tags:

```
/**
               ↓↓↓↓↓↓↓↓↓↓↓                         | ← This is a nested tag of the description.
 * Hello world {@see some} and blah-blah-blah.     |
   ↑↑↑↑↑↑↑↑↑↑↑             ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑     | ← These are plain text fragments.
 */
```

```php
/**
 * Tagged (composite) description structure pseudocode (real impl may differ)
 *
 * @template-implements IteratorAggregate<array-key, ComponentInterface>
 * @template-implements ArrayAccess<array-key, ComponentInterface>
 */
final class TaggedDescription implements
    DescriptionInterface,
    IteratorAggregate,
    ArrayAccess,
    Countable
{
    /** @var list<ComponentInterface> */
    public array $components;

    /** @var list<TagInterface> */
    public array $tags;
}
```

### Tag

A `Tag` is a name (without the leading `@`) and its optional description.

```
/**
    ↓↓↓↓↓↓                                 | ← This is a tag name.
 * @throws \Throwable An error occurred.   |
           ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑   | ← This is the tag suffix.
 */
```

```php
/**
 * Common tag structure pseudocode (real impl may differ)
 */
abstract class Tag implements TagInterface
{
    public string $name;

    public ?DescriptionInterface $description;
}
```

Known tags extend `Tag` with their own parsed parts. For example, `@see` exposes
the referenced symbol or URI, while `@link` exposes its URI:

```php
final class SeeTag extends Tag
{
    public UriReference|CodeReference $reference;
}

final class LinkTag extends Tag
{
    public UriReference $uri;
}
```

An unregistered tag is returned as a generic `Tag` whose whole suffix becomes
its description.
