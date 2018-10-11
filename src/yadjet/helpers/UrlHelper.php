<?php

namespace yadjet\helpers;

/**
 * URL Helper
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class UrlHelper
{

    /**
     * 解析 url 参数
     *
     * @param $url
     * @param $component
     * @param null $default
     * @return mixed|null
     */
    private static function _parse($url, $component, $default = null)
    {
        $res = parse_url(trim($url), $component);
        if (!$res && $default) {
            $res = $default;
        }

        return $res;
    }

    /**
     * 获取 scheme
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function scheme($url, $default = null)
    {
        return self::_parse($url, PHP_URL_SCHEME, $default);
    }

    /**
     * 获取 host
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function host($url, $default = null)
    {
        return self::_parse($url, PHP_URL_HOST, $default);
    }

    /**
     * 获取 port
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function port($url, $default = null)
    {
        return self::_parse($url, PHP_URL_PORT, $default);
    }

    /**
     * 获取 path
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function path($url, $default = null)
    {
        return self::_parse($url, PHP_URL_PATH, $default);
    }

    /**
     * 获取 query
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function query($url, $default = null)
    {
        return self::_parse($url, PHP_URL_QUERY, $default);
    }

    /**
     * 获取属有地址参数
     *
     * @param $url
     * @param array $default
     * @return array
     */
    public static function queries($url, $default = [])
    {
        $res = [];
        $query = self::query($url);
        if ($query) {
            foreach (explode('&', $query) as $item) {
                $items = explode('=', $item);
                $res[$items[0]] = isset($items[1]) ? $items[1] : '';
            }
        }

        if ($default && is_array($default)) {
            foreach ($default as $key => $value) {
                if (!isset($res[$key]) || empty($res[$key])) {
                    $res[$key] = $value;
                }
            }
        }

        return $res;
    }

    /**
     * 根据 $key 获取地址中的值
     *
     * @param $url
     * @param $key
     * @param null $default
     * @return null
     */
    public static function findQueryByKey($url, $key, $default = null)
    {
        $res = null;
        $query = self::query($url);
        if ($query) {
            $key = strtolower($key);
            foreach (explode('&', $query) as $item) {
                $items = explode('=', $item);
                if (strtolower($items[0]) == $key) {
                    $res = isset($items[1]) ? $items[1] : '';
                    break;
                }
            }
        }

        return $res ?: $default;
    }

    /**
     * 获取用户名
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function username($url, $default = null)
    {
        return self::_parse($url, PHP_URL_USER, $default);
    }

    /**
     * 获取密码
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function password($url, $default = null)
    {
        return self::_parse($url, PHP_URL_PASS, $default);
    }

    /**
     * 获取锚点
     *
     * @param $url
     * @param null $default
     * @return mixed|null
     */
    public static function fragment($url, $default = null)
    {
        return self::_parse($url, PHP_URL_FRAGMENT, $default);
    }

}
