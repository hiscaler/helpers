<?php

namespace yadjet\helpers;
require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class ArrayHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class ArrayHelperTest extends TestCase
{

    public function testRemoveEmpty()
    {
        $a = ['', '　', ' ', 'a', '1', 0, '0'];
        ArrayHelper::removeEmpty($a, true);
        $this->assertEquals(count($a), 5);

        $a = ['', '　', ' ', 'a', '1', 0, '0'];
        ArrayHelper::removeEmpty($a, true, '\x30'); // \x30 is `0`
        $this->assertEquals(count($a), 3);

        $a = ['', '　', ' ', 'a', '1', 0, '0'];
        ArrayHelper::removeEmpty($a, true, '\x30　'); // \x30 is `0`
        $this->assertSame(array_values($a), ['a', '1']);

        $a = ['', '　', ' ', 'a', '1', 0, '0'];
        ArrayHelper::removeEmpty($a, false, '\x30　'); // \x30 is `0`
        $this->assertEquals(count($a), 6);
    }

}
