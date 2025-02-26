<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\LineLexer;

class LineLexerTest extends TestCase{

    public function testTokenizeLine(){
        $this->assertSame(['A'],LineLexer::tokenizeLine('A'));
        $this->assertSame(['A','B'],LineLexer::tokenizeLine('A B'));
        $this->assertSame(['A','B','C'],LineLexer::tokenizeLine('A B C'));

        $this->assertSame(['"TEXT"'],LineLexer::tokenizeLine('"TEXT"'));
        $this->assertSame(['"TEXT"','"TEXT WITH SPACE"'],LineLexer::tokenizeLine('"TEXT" "TEXT WITH SPACE"'));
        $this->assertSame(['"TEXT"','"TEXT WITH SPACE"','"TEXT WITH \"ESCAPED\" QUOTES"','TEXT'],LineLexer::tokenizeLine('"TEXT" "TEXT WITH SPACE" "TEXT WITH \"ESCAPED\" QUOTES" TEXT'));
        $this->assertSame(['"TEXT"','"TEXT WITH SPACE"','"TEXT WITH ESCAPED \" QUOTE"','TEXT'],LineLexer::tokenizeLine('"TEXT" "TEXT WITH SPACE" "TEXT WITH ESCAPED \" QUOTE" TEXT'));

        $this->assertSame(['.'],LineLexer::tokenizeLine('.'));
        $this->assertSame(['@'],LineLexer::tokenizeLine('@'));
        $this->assertSame(['..'],LineLexer::tokenizeLine('..'));

        $this->assertSame(['\.'],LineLexer::tokenizeLine('\.'));
        $this->assertSame(['\@'],LineLexer::tokenizeLine('\@'));
        $this->assertSame(['\12'],LineLexer::tokenizeLine('\12'));
        $this->assertSame(['\34'],LineLexer::tokenizeLine('\34'));
    }

}