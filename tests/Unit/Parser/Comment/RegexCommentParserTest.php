<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit\Parser\Comment;

use TypeLang\PHPDoc\Parser\Comment\CommentParserInterface;
use TypeLang\PHPDoc\Parser\Comment\RegexCommentParser;

final class RegexCommentParserTest extends CommentParserTestCase
{
    public static function getCommentParser(): CommentParserInterface
    {
        return new RegexCommentParser();
    }
}
