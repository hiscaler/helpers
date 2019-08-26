<?php

namespace yadjet\helpers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;

/**
 * 时间处理
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class Time
{

    private static function _isImmutable(DateTimeInterface $d)
    {
        return $d instanceof DateTimeImmutable;
    }

    private static function _toDatetime(DateTimeInterface $d)
    {
        if (self::_isImmutable($d)) {
            $dt = new DateTime();

            return $dt->setTimestamp($d->getTimestamp());
        } else {
            return $d;
        }
    }

    /**
     * Datetime to DateTimeImmutable
     *
     * @param DateTimeInterface $d
     * @return bool|DateTimeImmutable|DateTimeInterface
     * @throws Exception
     */
    private static function _toDateTimeImmutable(DateTimeInterface $d)
    {
        if (!self::_isImmutable($d)) {
            $di = new DateTimeImmutable();

            return $di->setTimestamp($d->getTimestamp());
        } else {
            return $d;
        }
    }

    /**
     * 解析返回结果
     *
     * @param DateTimeInterface $beginDatetime
     * @param $endDatetime
     * @param $referenceDatetime
     * @return array|bool
     */
    private static function _parse(DateTimeInterface $beginDatetime, $endDatetime, $referenceDatetime)
    {
        if (self::_isImmutable($referenceDatetime)) {
            try {
                $b = self::_toDateTimeImmutable($beginDatetime);
                $e = self::_toDateTimeImmutable($endDatetime);
            } catch (Exception $e) {
                return false;
            }
        } else {
            $b = self::_toDatetime($beginDatetime);
            $e = self::_toDatetime($endDatetime);
        }

        return array(
            $b,
            $e
        );
    }

    /**
     * 指定年度起始时间
     *
     * @param DateTimeInterface $d
     * @return array|bool
     * @throws Exception
     */
    public static function year(DateTimeInterface $d)
    {
        try {
            $d0 = self::_toDateTimeImmutable($d);
            $b = $d0->setDate($d0->format('Y'), 1, 1)
                ->setTime(0, 0, 0);
            $e = $d0->setDate($d0->format("Y"), 12, 31)
                ->setTime(23, 59, 59);

            return self::_parse($b, $e, $d);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 指定月份起始时间
     *
     * @param DateTimeInterface $d
     * @return array
     * @throws Exception
     */
    public static function month(DateTimeInterface $d)
    {
        try {
            $d0 = self::_toDateTimeImmutable($d);
            $b = $d0->setDate($d0->format('Y'), $d0->format('m'), 1)
                ->setTime(0, 0, 0);
            $e = $d0->modify("+1 month")->modify("-1 day")
                ->setTime(23, 59, 59);

            return self::_parse($b, $e, $d);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 指定日期起始时间
     *
     * @param DateTimeInterface $d
     * @return array|bool
     * @throws Exception
     */
    public static function day(DateTimeInterface $d)
    {
        try {
            $d0 = self::_toDateTimeImmutable($d);

            return self::_parse($d0->setTime(0, 0, 0), $d0->setTime(23, 59, 59), $d);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}