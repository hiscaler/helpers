<?php

namespace yadjet\helpers;
require '../vendor/autoload.php';

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

}
