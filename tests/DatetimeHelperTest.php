<?php

namespace yadjet\helpers;
require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class DatetimeHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class DatetimeHelperTest extends TestCase
{

    public function testIncreaseMonths()
    {
        $this->assertEquals(DatetimeHelper::increaseMonths(201811, 1), 201812);
        $this->assertEquals(DatetimeHelper::increaseMonths(201811, 2), 201901);
        $this->assertEquals(DatetimeHelper::increaseMonths(201811, 11), 201910);
        $this->assertEquals(DatetimeHelper::increaseMonths(201811, 10), 201909);
        $this->assertEquals(DatetimeHelper::increaseMonths(20181101, 10), 20190901);
        $this->assertEquals(DatetimeHelper::increaseMonths("2018-01-02 15:12:13", 10, "Y-m-d H:i:s"), "2018-11-02 15:12:13");
        $this->assertEquals(DatetimeHelper::increaseMonths("2018-01-04", 20, "Y-m-d"), "2019-09-04");
    }

    public function testDecreaseMonths()
    {
        $this->assertEquals(DatetimeHelper::decreaseMonths(201811, 1), 201810);
        $this->assertEquals(DatetimeHelper::decreaseMonths(201811, 2), 201809);
        $this->assertEquals(DatetimeHelper::decreaseMonths(201811, 11), 201712);
        $this->assertEquals(DatetimeHelper::decreaseMonths(201811, 10), 201801);
        $this->assertEquals(DatetimeHelper::decreaseMonths(20181101, 10), 20180101);
        $this->assertEquals(DatetimeHelper::decreaseMonths("2018-01-02 15:12:13", 10, "Y-m-d H:i:s"), "2017-03-02 15:12:13");
        $this->assertEquals(DatetimeHelper::decreaseMonths("2018-01-04", 20, "Y-m-d"), "2016-05-04");
    }

    public function testDiff()
    {
        $this->assertEquals(DatetimeHelper::diff(201801, 201802), 31);
        $this->assertEquals(DatetimeHelper::diff(201801, 201802, 'y'), 0);
        $this->assertEquals(DatetimeHelper::diff(201801, 201802, 'm'), 1);
        $this->assertEquals(DatetimeHelper::diff(201801, 201912, 'm'), 10);
        $this->assertEquals(DatetimeHelper::diff(201801, 201802, 'd'), 0);
    }

    public function testRange()
    {
        $this->assertNotSame(DatetimeHelper::range(201801, 201812), [201801, 201802], 'Not Same Year month');
        $this->assertSame(DatetimeHelper::range(201801, 201803), [201801, 201802, 201803], 'Same Year month');
        $this->assertSame(DatetimeHelper::range(201801, 201903), [201801, 201802, 201803, 201804, 201805, 201806, 201807, 201808, 201809, 201810, 201811, 201812, 201901, 201902, 201903], 'Same Year month');
        $this->assertSame(DatetimeHelper::range(201801, 201803, 'y'), [2018], 'Same Year');
        $this->assertSame(DatetimeHelper::range(201801, 201902, 'y'), [2018, 2019], 'Same Year');
        $this->assertSame(DatetimeHelper::range(20180102, 20180103, 'ymd'), [20180102, 20180103], 'Same Year Month Day');
        $this->assertSame(DatetimeHelper::range(20180926, 20181001, 'ymd'), [20180926, 20180927, 20180928, 20180929, 20180930, 20181001], 'Same Year Month Day');
    }

    public function testIsTimestamp()
    {
        $this->assertEquals(DatetimeHelper::isTimestamp(111), false);
        $this->assertEquals(DatetimeHelper::isTimestamp(time()), true);
        $this->assertEquals(DatetimeHelper::isTimestamp('1537926598'), true);
        $this->assertEquals(DatetimeHelper::isTimestamp(1537926598), true);
        $this->assertEquals(DatetimeHelper::isTimestamp('abc'), false);
    }
}
