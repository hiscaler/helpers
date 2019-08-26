<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{

    public function testIsEmpty()
    {
        $this->assertEquals(true, StringHelper::isEmpty(' '));
        $this->assertEquals(true, StringHelper::isEmpty('　　　　　'), true);
        $this->assertEquals(false, StringHelper::isEmpty('　　　　　', ''));
        $this->assertEquals(true, StringHelper::isEmpty(null));
        $this->assertEquals(false, StringHelper::isEmpty(0));
        $this->assertEquals(true, StringHelper::isEmpty('0', '\x30'));
        $this->assertEquals(true, StringHelper::isEmpty(''));
    }

    public function testRemoveEmoji()
    {
        $this->assertEquals('hi', StringHelper::removeEmoji("👶hi"));
        $this->assertEquals("b", StringHelper::removeEmoji("👰b"));
        $this->assertEquals("a", StringHelper::removeEmoji("a👰"));
        $this->assertEquals("ab", StringHelper::removeEmoji("a👰b"));
        $this->assertEquals("", StringHelper::removeEmoji("👉🤟"));
        $this->assertEquals("123你好", StringHelper::removeEmoji("1👉2🤟👉👰3🤟👉👶你好🤟"));
        $this->assertEquals("123你好", StringHelper::removeEmoji("1👉2🤟👉👰3🤟👉👶你  　　好🤟"));
        $this->assertEquals("", StringHelper::removeEmoji(" "));
        $this->assertEquals(" ", StringHelper::removeEmoji(" ", false));
        $this->assertEquals("　", StringHelper::removeEmoji("　", false));
        $this->assertEquals("", StringHelper::removeEmoji("　", true));
    }

}
