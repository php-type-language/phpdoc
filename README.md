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

The reference PHPDoc parser for **TypeLang**. It turns a raw `/** ... */`
comment into an immutable, iterable object graph of its description and tags.

Full documentation is available at [typelang.dev](https://typelang.dev).

## Installation

Install the package via [Composer](https://getcomposer.org):

```sh
composer require type-lang/phpdoc
```

**Requirements:** 
- PHP 8.4+

## Usage

`DocBlockParser::parse()` returns a `DocBlock` — an immutable collection of the
comment's description and tags:

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

### Structure

A `DocBlock` is composed of three kinds of elements:

- **DocBlock** — the whole comment; behaves as a `Countable`, `IteratorAggregate`
  and `ArrayAccess` collection of its tags.
- **Description** — the leading text. A plain `Description` is just a string;
  a `TaggedDescription` also holds inline tags like `{@see ...}`.
- **Tag** — a name (without the leading `@`) and an optional description. Known
  tags (`@see`, `@link`, ...) extend the base `Tag` with their own parsed parts;
  an unregistered tag keeps its whole suffix as the description.

See the [documentation](https://typelang.dev) for the full list of supported tags.
