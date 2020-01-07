<?php

namespace yadjet\helpers;

use DateTime;

/**
 * 断言处理助手类
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class IsHelper
{

    /**
     * 是否为微信访问
     *
     * @return bool
     */
    public static function wechat()
    {
        return strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
    }

    /**
     * 是否为 Base64 格式的图片
     *
     * @param $s
     * @return bool
     */
    public static function base64Image($s)
    {
        return (substr($s, 0, 5) != 'data:' || @imagecreatefromstring(ImageHelper::base64Decode($s)) === false) ? false : true;
    }

    /**
     * 判断是否为图片
     *
     * @param $path
     * @return bool
     */
    public static function image($path)
    {
        $is = false;
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            if (@imagecreatefromstring($path)) {
                $is = true;
            }
        } else {
            if (is_string($path)) {
                $is = self::base64Image($path);
            }
            if (!$is) {
                // Is image path
                if (false !== ($imageInfo = @getimagesize($path))) {
                    list($width, $height) = $imageInfo;
                    if ($width && $height) {
                        $is = true;
                    }
                }
                if (!$is && isset($path['tmp_name'])) {
                    // Is File object
                    $is = self::image($path['tmp_name']);
                }
            }
        }

        return $is;
    }

    /**
     * 判断是否为 XMl
     *
     * @param $s
     * @return bool
     */
    public static function xml($s)
    {
        $xmlParser = xml_parser_create();
        if (!xml_parse($xmlParser, $s, true)) {
            xml_parser_free($xmlParser);

            return false;
        } else {
            xml_parser_free($xmlParser);

            return (json_decode(json_encode(simplexml_load_string($s)), true)) !== false;
        }
    }

    /**
     * 判断是否为 JSON 格式字符串
     *
     * @param $s
     * @return bool
     */
    public static function json($s)
    {
        return !empty($s) && is_string($s) && is_array(json_decode($s, true)) && json_last_error() == 0;
    }

    /**
     * 判断当前是否在 CLI 模式下
     *
     * @return bool
     */
    public static function cli()
    {
        if (defined('STDIN')) {
            return true;
        }

        if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        }

        return false;
    }

    /**
     * 验证是否为有效的 Unix 时间戳
     *
     * @param $timestamp
     * @return bool
     */
    public static function timestamp($timestamp)
    {
        return (ctype_digit($timestamp) && strlen($timestamp) == 10 && strtotime(date('Y-m-d H:i:s', $timestamp)) === (int) $timestamp);
    }

    /**
     * 判断是否为有效的时间
     *
     * @param $value
     * @return bool
     */
    public static function datetime($value)
    {
        if ($value instanceof DateTime) {
            return true;
        }

        return !(strtotime($value) === false);
    }

    /**
     * 判断是否为 windows 系统
     *
     * @return bool
     */
    public static function windows()
    {
        return DIRECTORY_SEPARATOR === '\\';
    }

}