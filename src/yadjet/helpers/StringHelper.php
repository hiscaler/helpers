<?php

namespace yadjet\helpers;

class StringHelper {

    // 全角转半角
    public static function makeSemiangle($str) {
        $arr = ['０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => ',', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ', '＄' => '$', '＠' => '@', '＃' => '#', '＾' => '^', '＆' => '&', '＊' => '*',
            '＂' => '"'];

        return strtr($str, $arr);
    }

    // 计算字符串长度（汉字按两字符算）
    public static function strLen($str) {
        $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
        if ($length) {
            return strlen($str) - $length + intval($length / 3);
        } else {
            return strlen($str);
        }
    }

    // 正则高亮关键字
    function highlightWords($str, $words, $color = '#FFFF00') {
        if (is_array($words)) {
            foreach ($words as $k => $word) {
                $pattern[$k] = "/\b($word)\b/is";
                $replace[$k] = '<font style="background-color:' . $color . ';">\\1</font>';
            }
        } else {
            $pattern = "/\b($words)\b/is";
            $replace = '<font style="background-color:' . $color . ';">\\1</font>';
        }

        return preg_replace($pattern, $replace, $str);
    }

    public static function html2Text($str, $r = false) {
        return $r ? addslashes(self::_html2Text(stripslashes($str))) : self::_html2Text($str);
    }

    private static function _html2Text($str) {
        $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
        $alltext = "";
        $start = 1;
        for ($i = 0; $i < strlen($str); $i++) {
            if ($start == 0 && $str[$i] == ">") {
                $start = 1;
            } else if ($start == 1) {
                if ($str[$i] == "<") {
                    $start = 0;
                    $alltext .= " ";
                } else if (ord($str[$i]) > 31) {
                    $alltext .= $str[$i];
                }
            }
        }
        $alltext = str_replace("　", " ", $alltext);
        $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
        $alltext = preg_replace("/[ ]+/s", "", $alltext);
        return $alltext;
    }

    /**
     * 检测字符串是否为 UTF-8 编码
     * @param string $str
     * @return boolean
     */
    public static function isUtf8($str) {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254))
                    return false;
                elseif ($c >= 252)
                    $bits = 6;
                elseif ($c >= 248)
                    $bits = 5;
                elseif ($c >= 240)
                    $bits = 4;
                elseif ($c >= 224)
                    $bits = 3;
                elseif ($c >= 192)
                    $bits = 2;
                else
                    return false;
                if (($i + $bits) > $len)
                    return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191)
                        return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    //裁剪字符串，加“...”
    public static function subStr($str, $length, $endfix = '...') {
        mb_internal_encoding("UTF-8");
        $str_length = mb_strwidth($str);
        if ($str_length > $length * 2) {
            return mb_substr($str, 0, $length) . $endfix;
        } else {
            return $str;
        }
    }

    //裁剪字符串，不加“...”
    public static function cutStr($str, $startstr, $endstr) {
        $length = strlen($str);
        $start = mb_strpos($str, $startstr);
        $str = substr($str, $start, $length - $start);

        $end = mb_strpos($str, $endstr);
        return mb_substr($str, 0, $end);
    }

    //自动探测字符编码，并转换到指定编码

    public static function convertEncoding($data, $to) {
        $encode_arr = ['UTF-8', 'GBK', 'GB2312', 'BIG5', 'CP936'];
        if (get_extension_funcs('mbstring')) {
            $encoded = mb_detect_encoding($data, $encode_arr);
            $data = mb_convert_encoding($data, $to, $encoded);
        }
        return $data;
    }

    /**
      +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public static function msubStr($str, $start, $length, $charset = "utf-8", $suffix = '') {
        if (function_exists("mb_substr"))
            return mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));

        return (empty($suffix)) ? $slice : $slice . $suffix;
    }

    public static function generateRandomKey($length = 32) {
        if (!extension_loaded('mcrypt')) {
            throw new InvalidConfigException('The mcrypt PHP extension is not installed.');
        }
        $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        if ($bytes === false) {
            throw new Exception('Unable to generate random bytes.');
        }
        return $bytes;
    }

    /**
     * Generates a random string of specified length.
     * The string generated matches [A-Za-z0-9_-]+ and is transparent to URL-encoding.
     *
     * @param integer $length the length of the key in characters
     * @throws Exception Exception on failure.
     * @return string the generated random key
     */
    public static function generateRandomString($length = 32) {
        $bytes = self::generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }

    /**
     * 剔除字符串中的空格（包括中文和英文空格）
     * @param string $string
     * @return string
     */
    public static function removeSpace($string) {
        $string = preg_replace("/[\s]{2,}/", "", $string);
        $string = str_replace('　', '', $string);
        return str_replace(' ', '', $string);
    }

    // 获取汉语拼音的第一个首字母，默认返回大写
    public static function getFirstWordPinYin($string, $to_upper = true) {
        if (empty($string))
            return '';
        Yii::import('ext.HanZi.HanZiTools');
        $hz = new HanZiTools();
        $pinyin = '';
        $stringArray = explode(' ', $hz->pzhanzi_hanzi_to_pinyin($string));
        if (is_array($stringArray)) {
            foreach ($stringArray as $value) {
                $pinyin .= substr($value, 0, 1);
            }
        }
        $pinyin = substr($pinyin, 0, 1);

        return ($to_upper) ? strtoupper($pinyin) : strtolower($pinyin);
    }

    /**
     * 获取汉字拼音
     * @param string $string
     * @param boolean $ucFirst
     * @param boolean $polyphony
     * @param string $separator
     * @return string
     */
    public static function getPinYin($string, $ucFirst = true, $polyphony = true, $separator = '-') {
        if (empty($string)) {
            return '';
        }
        Yii::import('ext.pinYin.PinYin');
        $py = new PinYin();
        return $py->encode(trim(self::makeSemiangle($string)), $ucFirst, $polyphony, $separator);
    }

    public static function truncateText($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false) {
        if ($text == '') {
            return '';
        }

        $mbstring = extension_loaded('mbstring');
        if ($mbstring) {
            $old_encoding = mb_internal_encoding();
            @mb_internal_encoding(mb_detect_encoding($text));
        }
        $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
        $substr = ($mbstring) ? 'mb_substr' : 'substr';

        if ($strlen($text) > $length) {
            $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
            if ($truncate_lastspace) {
                $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
            }
            $text = $truncate_text . $truncate_string;
        }

//        if ($mbstring) {
//            @mb_internal_encoding($old_encoding);
//        }

        return $text;
    }

    public static function format($content, $formatType = 'markdown') {
        if (empty($content)) {
            return $content;
        }
        switch (strtolower($formatType)) {
            case 'markdown':
                $parser = new CMarkdownParser();
                return $parser->safeTransform($content);
                break;
            default:
                return $content;
        }
    }

    /**
     * 生成 UUID
     * @return string
     */
    public static function uuid() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            return substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
        }
    }

}
