<?php

namespace yadjet\helpers;

/**
 * Common Helper
 * @author hiscaler <hiscaler@gmail.com>
 */
class UtilHelper
{

    /**
     * 字符串转数组
     * @param string $string
     * @param string $delimiter
     * @return array
     */
    public static function string2array($string, $delimiter = ',')
    {
        return preg_split("/\s*{$delimiter}\s*/", trim($string), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 数组转字符串
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    public static function array2string($array, $delimiter = ',')
    {
        return implode($delimiter, $array);
    }

    /**
     * 获取浏览器名称
     * @return string
     */
    public static function getBrowserName()
    {
        $httpUserAgent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($httpUserAgent, "MSIE 9.0")) {
            return "Internet Explorer 9.0";
        } else if (strpos($httpUserAgent, "MSIE 8.0")) {
            return "Internet Explorer 8.0";
        } else if (strpos($httpUserAgent, "MSIE 7.0")) {
            return "Internet Explorer 7.0";
        } else if (strpos($httpUserAgent, "MSIE 6.0")) {
            return "Internet Explorer 6.0";
        } else if (strpos($httpUserAgent, "Firefox/3")) {
            return "Firefox 3";
        } else if (strpos($httpUserAgent, "Firefox/2")) {
            return "Firefox 2";
        } else if (strpos($httpUserAgent, "Chrome")) {
            return "Google Chrome";
        } else if (strpos($httpUserAgent, "Safari")) {
            return "Safari";
        } else if (strpos($httpUserAgent, "Opera")) {
            return "Opera";
        } else {
            return $httpUserAgent;
        }
    }

}
