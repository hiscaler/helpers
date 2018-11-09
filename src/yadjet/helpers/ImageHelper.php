<?php

namespace yadjet\helpers;

/**
 * Class ImageHelper
 *s
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class ImageHelper
{

    /**
     * 解析文本内容中的图片地址
     *
     * @param $str
     * @param int $index
     * @return array|null
     */
    public static function parseImages($str, $index = 0)
    {
        $images = null;
        if (!empty($str)) {
            $pattern = "/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/";
            preg_match_all($pattern, $str, $matches);
            if (isset($matches[1]) && !empty($matches[1])) {
                if ($index > 0) {
                    if (is_numeric($index) && isset($matches[1][$index - 1])) {
                        $images = $matches[1][$index - 1];
                    }
                } else {
                    $images = $matches[1];
                }
            }
        }

        return $images ? $images : ($index ? null : array());
    }

    /**
     * 获取图片可访问的全路径
     *
     * @param $image
     * @param string $url
     * @return string
     */
    public static function fullPath($image, $url = '')
    {
        if (strncasecmp($image, '//', 2) !== 0 && strncasecmp($image, 'http', 4) !== 0 && strncasecmp($image, 'https', 5) !== 0) {
            if ($url) {
                $urlConf = parse_url($url);
                if ($urlConf !== false) {
                    if (isset($urlConf['schema'])) {
                        $url .= $urlConf['schema'];
                    } else {
                        $url = 'http';
                    }
                    $url .= '://';
                    if (isset($urlConf['host'])) {
                        $url .= $urlConf['host'];
                    }
                    if (isset($urlConf['port']) && $urlConf['port'] != 80 && $urlConf['port'] != 443) {
                        $url .= ":{$urlConf['port']}";
                    }
                }
            }
            $image = $url . '/' . ltrim($image, ' / ');
        }

        return $image;
    }

    /**
     * 获取图片文件扩展名
     *
     * @param $image
     * @param bool $full
     * @param string $defaultExtension
     * @return string
     */
    public static function getExtension($image, $full = false, $defaultExtension = 'jpg')
    {
        $ext = null;
        // http://www.example.com/images/image/124/12460449.jpg?t=1537200428#a 此类情况需要处理
        if (strpos($image, '?') !== false) {
            $url = parse_url($image);
            if (isset($url['query']) || isset($url['fragment'])) {
                $schema = isset($url['scheme']) ? $url['scheme'] . ':' : '';
                $host = isset($url['host']) ? $url['host'] . '//' : '';

                $image = "{$schema}{$host}{$url['path']}";
            }
        }

        $fnGetImageType = function ($image) {
            if (function_exists('exif_imagetype')) {
                return exif_imagetype($image);
            } else {
                if ((list(, , $type) = getimagesize($image)) !== false) {
                    return $type;
                }

                return false;
            }
        };

        $type = @$fnGetImageType($image);
        if ($type !== false) {
            switch ($type) {
                case IMAGETYPE_GIF:
                    $ext = 'gif';
                    break;

                case IMAGETYPE_JPEG:
                    $ext = 'jpg';
                    break;

                case IMAGETYPE_PNG:
                    $ext = 'png';
                    break;

                case IMAGETYPE_SWF:
                    $ext = 'swf';
                    break;

                case IMAGETYPE_PSD :
                    $ext = 'psd';
                    break;

                case IMAGETYPE_BMP :
                    $ext = 'bmp';
                    break;

                case IMAGETYPE_TIFF_II :
                    $ext = 'tiff';
                    break;

                case IMAGETYPE_TIFF_MM :
                    $ext = 'tiff';
                    break;

                case IMAGETYPE_JPC  :
                    $ext = 'jpc';
                    break;

                case IMAGETYPE_JP2  :
                    $ext = 'jp2';
                    break;

                case IMAGETYPE_JPX  :
                    $ext = 'jpf';
                    break;

                case IMAGETYPE_JB2  :
                    $ext = 'jb2';
                    break;

                case IMAGETYPE_SWC  :
                    $ext = 'swc';
                    break;

                case IMAGETYPE_IFF  :
                    $ext = 'aiff';
                    break;

                case IMAGETYPE_WBMP  :
                    $ext = 'wbmp';
                    break;

                case IMAGETYPE_XBM  :
                    $ext = 'xbm';
                    break;

                default:
                    $ext = null;
                    break;
            }
        }

        if (empty($ext)) {
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            if (empty($ext)) {
                $res = getimagesizefromstring($image);
                $ext = image_type_to_extension($res[2]);
            }
        }

        return $ext ? ($full ? ".$ext" : $ext) : $defaultExtension;
    }

    /**
     * 图片 Base64 编码
     *
     * @param $filename
     * @param null $mimeType
     * @return string
     */
    public static function base64Encode($filename, $mimeType = null)
    {
        $imgBinary = fread(fopen($filename, "r"), filesize($filename));
        empty($mimeType) && $mimeType = mime_content_type($filename);

        return 'data:' . $mimeType . ';base64,' . base64_encode($imgBinary);
    }

    /**
     * 图片 Base64 解码
     *
     * @param $str
     * @return bool|string
     */
    public static function base64Decode($str)
    {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $str));
    }

}