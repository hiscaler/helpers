<?php

namespace yadjet\helpers;

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

}