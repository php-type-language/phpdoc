<a href="https://github.com/php-type-language" target="_blank">
    <picture>
        <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
    </picture>
</a>

---

<p align="center">
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc"><img src="https://poser.pugx.org/type-lang/phpdoc/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/phpdoc-parser/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/phpdoc/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/phpdoc-parser/actions"><img src="https://github.com/php-type-language/phpdoc-parser/workflows/tests/badge.svg"></a>
</p>

The PHP reference implementation for Type Language PhpDoc Parser.

Read [documentation pages](https://phpdoc.io) for more information.

## Installation

PhpDoc Generator is available as composer repository and can be
installed using the following command in a root of your project:

```sh
$ composer require type-lang/phpdoc
```

## Quick Start

```php
use TypeLang\PHPDoc\DocBlockFactory;

$phpdoc = DocBlockFactory::createInstance()
    ->create(<<<'PHP'
        /**
         * Example description.
         *
         * @param non-empty-string $foo Foo param.
         * @param int<0, max> $bar Bar param.
         *
         * @return void Returns nothing.
         */
        PHP);

echo $phpdoc->getDescription() . "\n";
// Output: string("Example description.\n")

foreach ($phpdoc->getTags() as $tag) {
    echo $tag->getName() . ': '
       . $tag->getDescription() . "\n";
    // Output 3 lines:
    //   param: Foo param.
    //   param: Bar param.
    //   return: Returns nothing.
}
```

### Supported Tags


