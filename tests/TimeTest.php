<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use DateTimeInterface;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class TimeTest extends TestCase
{

    public function testYear()
    {
        try {
            $dateFormat = "Y-m-d H:i:s";
            /* @var $b DateTimeInterface */
            /* @var $e DateTimeInterface */
            list($b, $e) = Time::year(new \DateTime("2019-02-01"));
            $this->assertEquals(array(
                "2019-01-01 00:00:00",
                '2019-12-31 23:59:59'
            ), array(
                $b->format($dateFormat),
                $e->format($dateFormat)
            ));

            $this->assertEquals(true, $b instanceof \DateTime);
            $this->assertEquals(true, $e instanceof \DateTime);
            $this->assertEquals(false, $e instanceof \DateTimeImmutable);
            $this->assertEquals(false, $e instanceof \DateTimeImmutable);
        } catch (Exception $e) {
            throw new \PHPUnit\Runner\Exception($e->getMessage());
        }
    }

    public function testQuarter()
    {
        $dateFormat = "Y-m-d H:i:s";
        /* @var $b DateTimeInterface */
        /* @var $e DateTimeInterface */
        list($b, $e) = Time::quarter(new \DateTime("2019-02-01"));
        $this->assertEquals(array(
            "2019-01-01 00:00:00",
            '2019-03-31 23:59:59'
        ), array(
            $b->format($dateFormat),
            $e->format($dateFormat)
        ));

        list($b, $e) = Time::quarter(new \DateTime("2019-03-31"));
        $this->assertEquals(array(
            "2019-01-01 00:00:00",
            '2019-03-31 23:59:59'
        ), array(
            $b->format($dateFormat),
            $e->format($dateFormat)
        ));

        list($b, $e) = Time::quarter(new \DateTime("2019-08-21"));
        $this->assertEquals(array(
            "2019-07-01 00:00:00",
            '2019-09-30 23:59:59'
        ), array(
            $b->format($dateFormat),
            $e->format($dateFormat)
        ));

        $this->assertEquals(true, $b instanceof \DateTime);
        $this->assertEquals(true, $e instanceof \DateTime);
        $this->assertEquals(false, $e instanceof \DateTimeImmutable);
        $this->assertEquals(false, $e instanceof \DateTimeImmutable);
    }

    public function testMonth()
    {
        try {
            $this->assertEquals(array(
                new \DateTime("2019-01-01 00:00:00"),
                new \DateTime('2019-01-31 23:59:59')
            ), Time::month(new \DateTime("2019-01-01")));

            $this->assertEquals(array(
                new \DateTime("2019-02-01 00:00:00"),
                new \DateTime('2019-02-28 23:59:59')
            ), Time::month(new \DateTime("2019-02-01")));
        } catch (Exception $e) {
            throw new \PHPUnit\Runner\Exception($e->getMessage());
        }
    }

    public function testDay()
    {
        try {
            $this->assertEquals(array(
                new \DateTime("2019-01-01 00:00:00"),
                new \DateTime('2019-01-01 23:59:59')
            ), Time::day(new \DateTime("2019-01-01")));

            $this->assertEquals(array(
                new \DateTime("2019-02-01 00:00:00"),
                new \DateTime('2019-02-01 23:59:59')
            ), Time::day(new \DateTime("2019-02-01")));
        } catch (Exception $e) {
            throw new \PHPUnit\Runner\Exception($e->getMessage());
        }
    }

}
