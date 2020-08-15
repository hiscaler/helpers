<?php

namespace yadjet\helpers;

/**
 * IP 处理助手
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class IpHelper
{

    /**
     * IP V4 To V6
     *
     * @param $ip
     * @return string
     */
    public static function v426($ip)
    {
        if (!$ip || !filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
            return $ip;
        }
        $segments = array();
        foreach (explode('.', $ip) as $k => $value) {
            $s = base_convert($value, 10, 16);
            if (strlen($s) == 1) {
                $s = '0' . $s;
            }
            $segments[$k] = $s;
        }

        return '0000:0000:0000:0000:0000:ffff:' . $segments[0] . $segments[1] . ':' . $segments[2] . $segments[3];
    }

    /**
     * IP V6 To V4
     *
     * @param $ip
     * @return string
     */
    public static function v624($ip)
    {
        if (!$ip || !filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
            return $ip;
        }
        $segments = explode(':', mb_substr($ip, 30, 38));

        return sprintf("%s.%s.%s.%s",
            base_convert(mb_substr($segments[0], 0, 2), 16, 10),
            base_convert(mb_substr($segments[0], 2, 4), 16, 10),
            base_convert(mb_substr($segments[1], 0, 2), 16, 10),
            base_convert(mb_substr($segments[1], 2, 4), 16, 10)
        );
    }

}