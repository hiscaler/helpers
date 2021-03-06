<?php

namespace yadjet\helpers;

/**
 * URL 处理助手类
 *
 * @package yadjet\helpers
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
        $pairs = array();
        if ($query) {
            if (stripos($query, "&$key=") !== false && $ignore) {
                $append = false;
            } else {
                foreach (explode('&', $query) as $item) {
                    if (stripos($item, '=') !== false) {
                        list($k, $v) = explode('=', $item);
                        if ($k === '') {
                            // e.g. a=1&=2
                            $v = $item;
                        } else {
                            // e.g. a=1&b=2 or a=1&b=
                            if (strtolower($k) == strtolower($key)) {
                                if ($ignore) {
                                    return $url;
                                } else {
                                    !$ignore && $v = $value;
                                }

                                $append = false;
                            }
                            $v = "$k=$v";
                        }
                    } else {
                        // e.g. a=1&2
                        $v = $item;
                    }
                    $pairs[] = $v;
                }
            }
        }

        if ($append) {
            if (stripos($url, '?') === false) {
                $url .= "?$key=$value";
            } else {
                $url .= "&$key=$value";
            }
        } elseif ($pairs) {
            $url = str_replace($query, implode('&', $pairs), $url);
        }

        return $url;
    }

    /**
     * 字符串解码
     *
     * @param $str
     * @param int $times
     * @return string
     */
    private static function _decode($str, $times = 1)
    {
        for ($i = 1; $i <= $times; $i++) {
            $str = urldecode($str);
        }

        return $str;
    }

    /**
     * Url 内容解码
     *
     * @param $url
     * @return mixed
     */
    public static function decode($url)
    {
        if (preg_match("/%[A-Z0-9%+-_]/i", $url)) {
            $url = preg_replace('/%EF%BF%BD/', '', $url); // 移除错误的编码
            $url = rawurldecode($url);
            if (preg_match("/%[A-Z0-9%+-_]/i", $url)) {
                $times = 1;
            } else {
                $times = 0;
            }
            // 判断是否多次编码
            if (($index = strpos($url, "%25")) !== false) {
                $index += 3;
                $times++;
                for ($i = 1, $n = strlen($url) - $index; $i <= $n; $i++) {
                    if (substr($url, $index, 2) == '25') {
                        $times++;
                        $index += 2;
                    } else {
                        break;
                    }
                }
            }
            $query = self::query($url);
            $pairs = array();
            if ($query) {
                foreach (explode('&', $query) as $item) {
                    if (stripos($item, '=') !== false) {
                        list($k, $v) = explode('=', $item);
                        if ($k === '') {
                            // e.g. a=1&=2
                            $v = self::_decode($item, $times);
                        } else {
                            // e.g. a=1&b=2 or a=1&b=
                            $v = "$k=" . self::_decode($v, $times);
                        }
                    } else {
                        // e.g. a=1&2
                        $v = self::_decode($item, $times);
                    }
                    $pairs[] = $v;
                }
            }

            if ($pairs) {
                $url = str_replace($query, implode('&', $pairs), $url);
            }
        }

        if (preg_match('/[' . chr(0xa1) . '-' . chr(0xff) . ']+$/', $url)) {
            // 判断是否包含中文，包含的话则需要转码
            $url = iconv('GBK', 'UTF-8//IGNORE', $url);
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

    /**
     * 是否绝对路径
     *
     * @param $url
     * @return bool
     */
    public static function isAbsolute($url)
    {
        if (stripos($url, '//') === 0 ||
            self::scheme($url) ||
            self::host($url)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否相对路径
     *
     * @param $url
     * @return bool
     */
    public static function isRelative($url)
    {
        return !self::isAbsolute($url);
    }

}
