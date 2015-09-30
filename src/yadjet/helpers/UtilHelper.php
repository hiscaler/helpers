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

    /**
     * Check value to find if it was serialized.   
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @since 2.0.5
     * @link https://core.trac.wordpress.org/browser/tags/4.3.1/src/wp-includes/functions.php WordPress
     * 
     * @param string $data   Value to check to see if was serialized.
     * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
     * @return bool False if not serialized and true if it was.
     */
    public static function isSerialized($data, $strict = true)
    {
        // if it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's' :
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
        }

        return false;
    }

    /**
     * Check whether serialized data is of string type.
     *
     * @since 2.0.5
     * @link https://core.trac.wordpress.org/browser/tags/4.3.1/src/wp-includes/functions.php WordPress
     *
     * @param string $data Serialized data.
     * @return bool False if not a serialized string, true if it is.
     */
    public static function isSerializedString($data)
    {
        // if it isn't a string, it isn't a serialized string.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if (strlen($data) < 4) {
            return false;
        } elseif (':' !== $data[1]) {
            return false;
        } elseif (';' !== substr($data, -1)) {
            return false;
        } elseif ($data[0] !== 's') {
            return false;
        } elseif ('"' !== substr($data, -2, 1)) {
            return false;
        } else {
            return true;
        }
    }

}
