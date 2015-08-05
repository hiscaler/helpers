<?php

namespace yadjet\helpers;

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
                $valid = self::checksum18($identityCardNumber);
                break;
            case 18:
                $valid = self::checksum18(self::change15to18($identityCardNumber));
                break;
            default:
                $valid = false;
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
        //加权因子
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

}
