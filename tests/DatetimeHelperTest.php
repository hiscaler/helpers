<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

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
        $this->assertEquals(201812, DatetimeHelper::increaseMonths(201811, 1));
        $this->assertEquals(201901, DatetimeHelper::increaseMonths(201811, 2));
        $this->assertEquals(201910, DatetimeHelper::increaseMonths(201811, 11));
        $this->assertEquals(201909, DatetimeHelper::increaseMonths(201811, 10));
        $this->assertEquals(20190901, DatetimeHelper::increaseMonths(20181101, 10));
        $this->assertEquals("2018-11-02 15:12:13", DatetimeHelper::increaseMonths("2018-01-02 15:12:13", 10, "Y-m-d H:i:s"));
        $this->assertEquals("2019-09-04", DatetimeHelper::increaseMonths("2018-01-04", 20, "Y-m-d"));
    }

    public function testDecreaseMonths()
    {
        $this->assertEquals(201810, DatetimeHelper::decreaseMonths(201811, 1));
        $this->assertEquals(201809, DatetimeHelper::decreaseMonths(201811, 2));
        $this->assertEquals(201712, DatetimeHelper::decreaseMonths(201811, 11));
        $this->assertEquals(201801, DatetimeHelper::decreaseMonths(201811, 10));
        $this->assertEquals(20180101, DatetimeHelper::decreaseMonths(20181101, 10));
        $this->assertEquals("2017-03-02 15:12:13", DatetimeHelper::decreaseMonths("2018-01-02 15:12:13", 10, "Y-m-d H:i:s"));
        $this->assertEquals("2016-05-04", DatetimeHelper::decreaseMonths("2018-01-04", 20, "Y-m-d"));
    }

    public function testDiff()
    {
        $this->assertEquals(31, DatetimeHelper::diff(201801, 201802));
        $this->assertEquals(0, DatetimeHelper::diff(201801, 201802, 'y'));
        $this->assertEquals(1, DatetimeHelper::diff(201801, 201802, 'm'));
        $this->assertEquals(10, DatetimeHelper::diff(201801, 201912, 'm'));
        $this->assertEquals(0, DatetimeHelper::diff(201801, 201802, 'd'));
    }

    public function testRange()
    {
        $this->assertNotSame(array(201801, 201802), DatetimeHelper::range(201801, 201812), 'Not Same Year month');
        $this->assertSame(array(201801, 201802, 201803), DatetimeHelper::range(201801, 201803), 'Same Year month');
        $this->assertSame(array(201801, 201802, 201803, 201804, 201805, 201806, 201807, 201808, 201809, 201810, 201811, 201812, 201901, 201902, 201903), DatetimeHelper::range(201801, 201903), 'Same Year month');
        $this->assertSame(array(2018), DatetimeHelper::range(201801, 201803, 'y'), 'Same Year');
        $this->assertSame(array(2018, 2019), DatetimeHelper::range(201801, 201902, 'y'), 'Same Year');
        $this->assertSame(array(20180102, 20180103), DatetimeHelper::range(20180102, 20180103, 'ymd'), 'Same Year Month Day');
        $this->assertSame(array(20180926, 20180927, 20180928, 20180929, 20180930, 20181001), DatetimeHelper::range(20180926, 20181001, 'ymd'), 'Same Year Month Day');
    }

    public function testIsTimestamp()
    {
        $this->assertEquals(false, DatetimeHelper::isTimestamp(111));
        $this->assertEquals(true, DatetimeHelper::isTimestamp(time()));
        $this->assertEquals(true, DatetimeHelper::isTimestamp('1537926598'));
        $this->assertEquals(true, DatetimeHelper::isTimestamp(1537926598));
        $this->assertEquals(false, DatetimeHelper::isTimestamp('abc'));
    }

    public function testYearWeekRange()
    {
        $items = array(
            array('year' => 2019, 'week' => 1, 'expected' => '2018-12-30/2019-01-05'),
            array('year' => 2019, 'week' => 2, 'expected' => '2019-01-06/2019-01-12'),
            array('year' => 2019, 'week' => 3, 'expected' => '2019-01-13/2019-01-19'),
            array('year' => 2019, 'week' => 4, 'expected' => '2019-01-20/2019-01-26'),
            array('year' => 2019, 'week' => 5, 'expected' => '2019-01-27/2019-02-02'),
        );
        foreach ($items as $item) {
            list($bt, $et) = DatetimeHelper::yearWeekRange($item['year'], $item['week']);
            $expected = explode('/', $item['expected']);
            $datetime = new \DateTime();
            $b = $datetime->setTimestamp($bt)->format("Y-m-d");
            $e = $datetime->setTimestamp($et)->format("Y-m-d");
            $actual = array($b, $e);
            $this->assertEquals($expected, $actual, "{$item['year']} 年第 {$item['week']} 周 期待：" .
                var_export($expected, true) .
                ", 实际：" .
                var_export($actual, true)
            );
        }
    }

}
