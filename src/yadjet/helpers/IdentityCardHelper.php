<?php

namespace yadjet\helpers;

use DateTime;
use Exception;

/**
 * 身份证辅助函数
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class IdentityCardHelper
{

    /**
     * 身份证号码是否有效
     *
     * @param string $identityCardNumber
     * @return boolean
     */
    public static function isValid($identityCardNumber)
    {
        switch (strlen($identityCardNumber)) {
            case 15:
                $valid = self::checksum18(self::change15to18($identityCardNumber));
                break;

            case 18:
                $valid = self::checksum18($identityCardNumber);
                break;

            default:
                $valid = false;
                break;
        }

        return $valid;
    }

    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     *
     * @param string $identityCardBase
     * @return string|boolean
     */
    private static function verifyNumber($identityCardBase)
    {
        $len = strlen($identityCardBase);
        if ($len != 17) {
            return false;
        }
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verifyNumberList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < $len; $i++) {
            $checksum += substr($identityCardBase, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verifyNumber = $verifyNumberList[$mod];

        return $verifyNumber;
    }

    /**
     * 将15位身份证升级到18位
     *
     * @param string $idCardNumber
     * @return string|boolean
     */
    public static function change15to18($idCardNumber)
    {
        if (strlen($idCardNumber) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idCardNumber, 12, 3), array('996', '997', '998', '999')) !== false) {
                $idCardNumber = substr($idCardNumber, 0, 6) . '18' . substr($idCardNumber, 6, 9);
            } else {
                $idCardNumber = substr($idCardNumber, 0, 6) . '19' . substr($idCardNumber, 6, 9);
            }
        }
        $idCardNumber = $idCardNumber . self::verifyNumber($idCardNumber);

        return $idCardNumber;
    }

    /**
     * 18位身份证校验码有效性检查
     *
     * @param string $identityCardNumber
     * @return boolean
     */
    private static function checksum18($identityCardNumber)
    {
        if (strlen($identityCardNumber) != 18) {
            return false;
        }
        $identityCardBase = substr($identityCardNumber, 0, 17);
        if (self::verifyNumber($identityCardBase) != strtoupper(substr($identityCardNumber, 17, 1))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 根据身份证号码获取出生年月
     *
     * @param $identityCardNumber
     * @param null $format 日期格式（Y-m-d, Ymd 之类）
     * @return int|null|string
     */
    public static function getBirthday($identityCardNumber, $format = null)
    {
        $birthday = null;
        if (self::isValid($identityCardNumber)) {
            $yearMonthDay = strlen($identityCardNumber) == 15 ? ('19' . substr($identityCardNumber, 6, 6)) : substr($identityCardNumber, 6, 8);
            $datetime = new DateTime();
            $datetime->setDate(substr($yearMonthDay, 0, 4), substr($yearMonthDay, 4, 2), substr($yearMonthDay, 6, 2));
            try {
                $birthday = $format ? $datetime->format($format) : $datetime->getTimestamp();
            } catch (Exception $e) {
            }
        }

        return $birthday;
    }

    /**
     * 根据身份证号码获取年龄
     *
     * @param $identityCardNumber
     * @return int|null
     * @throws Exception
     */
    public static function getAge($identityCardNumber)
    {
        $age = null;
        if (self::isValid($identityCardNumber)) {
            $age = (new DateTime(self::getBirthday($identityCardNumber, 'Y-m-d')))->diff(new DateTime())->y;
        }

        return $age;
    }

    /**
     * 根据身份证获取性别（0 未知, 1 男, 2 女）
     *
     * @param $identityCardNumber
     * @return int|null
     */
    public static function getSex($identityCardNumber)
    {
        $sex = 0;
        if (self::isValid($identityCardNumber)) {
            $sex = substr($identityCardNumber, (strlen($identityCardNumber) == 15 ? -1 : -2), 1) % 2 ? 1 : 2;
        }

        return $sex;
    }

}
