<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

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
        $a = array('', '　', ' ', 'a', '1', 0, '0');
        ArrayHelper::removeEmpty($a, true);
        $this->assertEquals(5, count($a));

        $a = array('', '　', ' ', 'a', '1', 0, '0');
        ArrayHelper::removeEmpty($a, true, '\x30'); // \x30 is `0`
        $this->assertEquals(3, count($a));

        $a = array('', '　', ' ', 'a', '1', 0, '0');
        ArrayHelper::removeEmpty($a, true, '\x30　'); // \x30 is `0`
        $this->assertSame(array('a', '1'), array_values($a));

        $a = array('', '　', ' ', 'a', '1', 0, '0');
        ArrayHelper::removeEmpty($a, false, '\x30　'); // \x30 is `0`
        $this->assertEquals(6, count($a));
    }

    public function testGetColumn()
    {
        $rows = array(
            array('id' => 1, 'value' => '1-1'),
            array('id' => 2, 'value' => '2-1'),
        );
        $this->assertSame(array(1, 2), ArrayHelper::getColumn($rows, 'id'));
        $this->assertSame(array('1-1', '2-1'), ArrayHelper::getColumn($rows, 'value'));

        $rows = array(
            array('id' => 1, 'children' => array('id' => 11)),
            array('id' => 2, 'children' => array('id' => 22)),
        );
        $this->assertSame(array(array('id' => 11), array('id' => 22)), ArrayHelper::getColumn($rows, 'children'));
    }

    public function testIsOneDimension()
    {
        $this->assertTrue(ArrayHelper::isOneDimension(array(1, 2)));
        $this->assertTrue(ArrayHelper::isOneDimension(array(1, 2, 0, 2, false, 'abc')));
        $this->assertFalse(ArrayHelper::isOneDimension(array(1, array())));
        $this->assertFalse(ArrayHelper::isOneDimension(array(1, array(1, 2))));
    }

}
