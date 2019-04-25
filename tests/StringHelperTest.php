<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{

    public function testIsEmpty()
    {
        $this->assertEquals(StringHelper::isEmpty(' '), true);
        $this->assertEquals(StringHelper::isEmpty('　　　　　'), true);
        $this->assertEquals(StringHelper::isEmpty('　　　　　', ''), false);
        $this->assertEquals(StringHelper::isEmpty(null), true);
        $this->assertEquals(StringHelper::isEmpty(0), false);
        $this->assertEquals(StringHelper::isEmpty('0', '\x30'), true);
        $this->assertEquals(StringHelper::isEmpty(''), true);
    }

    public function testRemoveEmoji()
    {
        $this->assertEquals(StringHelper::removeEmoji("👶hi"), "hi");
        $this->assertEquals(StringHelper::removeEmoji("👰b"), "b");
        $this->assertEquals(StringHelper::removeEmoji("a👰"), "a");
        $this->assertEquals(StringHelper::removeEmoji("a👰b"), "ab");
        $this->assertEquals(StringHelper::removeEmoji("👉🤟"), "");
        $this->assertEquals(StringHelper::removeEmoji("1👉2🤟👉👰3🤟👉👶你好🤟"), "123你好");
        $this->assertEquals(StringHelper::removeEmoji("1👉2🤟👉👰3🤟👉👶你  　　好🤟"), "123你好");
        $this->assertEquals(StringHelper::removeEmoji(" "), "");
        $this->assertEquals(StringHelper::removeEmoji(" ", false), " ");
        $this->assertEquals(StringHelper::removeEmoji("　", false), "　");
        $this->assertEquals(StringHelper::removeEmoji("　", true), "");
    }

}
