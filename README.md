<a href="https://github.com/php-type-language" target="_blank">
    <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
</a>

---

<p align="center">
    <a href="https://packagist.org/packages/type-lang/phpdoc-parser"><img src="https://poser.pugx.org/type-lang/phpdoc-parser/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc-parser"><img src="https://poser.pugx.org/type-lang/phpdoc-parser/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc-parser"><img src="https://poser.pugx.org/type-lang/phpdoc-parser/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/phpdoc-parser/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/phpdoc-parser/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/phpdoc-parser/actions"><img src="https://github.com/php-type-language/phpdoc-parser/workflows/tests/badge.svg"></a>
</p>

The PHP reference implementation for Type Language PhpDoc Parser.

Read [documentation pages](https://phpdoc.io) for more information.

## Installation

Type Language PHPDoc Parser is available as Composer repository and can
be installed using the following command in a root of your project:

```sh
$ composer require type-lang/phpdoc-parser
```

## Quick Start

```php
$parser = new \TypeLang\PHPDoc\Parser();
$result = $parser->parse(<<<'PHPDOC'
    /**
     * Example description {@see some} and blah-blah-blah.
     *
     * @Example\Annotation("foo")
     * @return array<non-empty-string, TypeStatement>
     * @throws \Throwable
     */
    PHPDOC);

var_dump($result);
```

**Expected Output:**
```
TypeLang\PHPDoc\DocBlock {
  -description: TypeLang\PHPDoc\Tag\Description\Description {
    -template: "Example description %1$s and blah-blah-blah."
    -tags: array:1 [
      0 => TypeLang\PHPDoc\Tag\Tag {
        #description: TypeLang\PHPDoc\Tag\Description\Description {
          -template: "some"
          -tags: []
        }
        #name: "see"
      }
    ]
  }
  -tags: array:3 [
    0 => TypeLang\PHPDoc\Tag\Tag {
      #description: TypeLang\PHPDoc\Tag\Description\Description {
        -template: "("foo")"
        -tags: []
      }
      #name: "Example\Annotation"
    }
    1 => TypeLang\PHPDoc\Tag\Tag {
      #description: TypeLang\PHPDoc\Tag\Description\Description {
        -template: "array<non-empty-string, TypeStatement>"
        -tags: []
      }
      #name: "return"
    }
    2 => TypeLang\PHPDoc\Tag\Tag {
      #description: TypeLang\PHPDoc\Tag\Description\Description {
        -template: "\Throwable"
        -tags: []
      }
      #name: "throws"
    }
  ]
}
```

### Structural Elements

**DocBlock**

DocBlock is a representation of the comment object.

```php
/**                                 |
 * Hello world                      | ← DocBlock's description.
 *                                  |
 * @param int $example              | ← DocBlock's tag #1.
 * @throws \Throwable Description   | ← DocBlock's tag #2.
 */                                 |
```

- `getDescription()` ― Provides a `Description` object.
- `getTags()` ― Provides a list of `Tag` objects.

```php
/** @template-implements \Traversable<array-key, Tag> */
class DocBlock implements \Traversable
{
    public function getDescription(): Description;
    
    /** @return list<Tag> */
    public function getTags(): array;
}
```

**Description**

Description is a representation of the description object which may contain
other tags.

```php
/**
               ↓↓↓↓↓↓↓↓↓↓↓                         | ← This is a nested tag of the description.
 * Hello world {@see some} and blah-blah-blah.     |
   ↑↑↑↑↑↑↑↑↑↑↑             ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑     | ← This is part of the template.
 */
```

- `getTemplate()` ― Provides a sprintf-formatted template string of the description.
- `getTags()` ― Provides a list of `Tag` objects.

```php
/** @template-implements \Traversable<array-key, Tag> */
class Description implements \Traversable, \Stringable
{
    public function getTemplate(): string;

    /** @return list<Tag> */
    public function getTags(): array;
}
```

**Tag**

A Tag represents a name (ID) and its contents.

```php
/**
    ↓↓↓↓↓↓                                 | ← This is a tag name.
 * @throws \Throwable An error occurred.   |
           ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑   | ← This is tag description.
 */
```

- `getName()` ― Provides a tag's name (ID).
- `getDescription()` ― Provides an optional description of the tag.

```php
class Tag implements \Stringable
{
    public function getName(): string;

    public function getDescription(): ?Description;
}
```
