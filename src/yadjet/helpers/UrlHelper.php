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
        $items = parse_url(trim($url));
        if (isset($items['host'])) {
            $host = $items['host'];
        } elseif (isset($items['path'])) {
            if (stripos($items['path'], '/') !== false) {
                $path = explode('/', $items['path'], 2);
                $host = array_shift($path);
            } else {
                $host = $items['path'];
            }
        } else {
            $host = null;
        }

        return $host ?: $default;
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
    public static function queries($url, $default = array())
    {
        $res = array();
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
     * @return null|string
     */
    public static function findQueryValueByKey($url, $key, $default = null)
    {
        $res = null;
        $query = self::query($url);
        if ($query &&
            stripos($query, '&') !== false &&
            ($key = strtolower(trim($key))) &&
            stripos($query, $key) !== false
        ) {
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
     * 添加 Query 参数
     *
     * @param $url
     * @param $key
     * @param $value
     * @param bool $ignore
     * @return mixed|string
     */
    public static function addQueryParam($url, $key, $value, $ignore = true)
    {
        $key = trim($key);
        if ($key === '' || $key === null) {
            return $url;
        }

        $query = self::query($url);

        $append = true;
        if ($query) {
            if ((stripos($query, "?$key=") !== false || stripos($query, "&$key=") !== false) && $ignore) {
                return $url;
            } else {
                foreach (explode('&', $query) as $i => $item) {
                    $items = explode('=', $item);
                    if (strtolower($items[0]) == strtolower($key)) {
                        foreach (array("$item&", $item) as $s) {
                            if (stripos($url, $s) !== false) {
                                $url = str_replace($s, $items[0] . '=' . $value, $url);
                                $append = false;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        if ($append) {
            if (stripos($url, '&') === false) {
                $url .= "?$key=$value";
            } else {
                $url .= "&$key=$value";
            }
        }

        return $url;
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
