<?php

namespace yadjet\helpers;
require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use function var_dump;

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

    public function testGetColumn()
    {
        $rows = array(
            array('id' => 1, 'value' => '1-1'),
            array('id' => 2, 'value' => '2-1'),
        );
        $this->assertSame(ArrayHelper::getColumn($rows, 'id'), [1, 2]);
        $this->assertSame(ArrayHelper::getColumn($rows, 'value'), ['1-1', '2-1']);

        $rows = array(
            array('id' => 1, 'children' => ['id' => 11]),
            array('id' => 2, 'children' => ['id' => 22]),
        );
        $this->assertSame(ArrayHelper::getColumn($rows, 'children'), [['id' => 11], ['id' => 22]]);
    }

}
