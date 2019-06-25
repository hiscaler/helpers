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

}