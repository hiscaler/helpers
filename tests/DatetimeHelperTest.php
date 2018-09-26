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

    public function testIsTimestamp()
    {
        $this->assertEquals(DatetimeHelper::isTimestamp(111), false);
        $this->assertEquals(DatetimeHelper::isTimestamp(time()), true);
        $this->assertEquals(DatetimeHelper::isTimestamp('1537926598'), true);
        $this->assertEquals(DatetimeHelper::isTimestamp(1537926598), true);
        $this->assertEquals(DatetimeHelper::isTimestamp('abc'), false);
    }
}
