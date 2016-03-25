<?php

namespace yadjet\helpers;

/**
 * 时间处理助手
 */
class DatetimeHelper
{

    //将任意时间字符串转化成时间戳
    public static function mktime($dtime)
    {
        if (!preg_match("/[^0-9]/", $dtime)) {
            return $dtime;
        }

        $dt = [1970, 1, 1, 0, 0, 0];
        $dtime = preg_replace("/[\r\n\t]|日|秒/", " ", $dtime);
        $dtime = str_replace(".", "-", $dtime);
        $dtime = str_replace("/", "-", $dtime);
        $dtime = str_replace("年", "-", $dtime);
        $dtime = str_replace("月", "-", $dtime);
        $dtime = str_replace("时", ":", $dtime);
        $dtime = str_replace("分", ":", $dtime);
        $dtime = trim(preg_replace("/[ ]{1,}/", " ", $dtime));
        $ds = explode(" ", $dtime);
        $ymd = explode("-", $ds[0]);
        if (isset($ymd[0])) {
            $dt[0] = $ymd[0];
        }
        if (isset($ymd[1])) {
            $dt[1] = $ymd[1];
        }
        if (isset($ymd[2])) {
            $dt[2] = $ymd[2];
        }
        if (strlen($dt[0]) == 2) {
            $dt[0] = '20' . $dt[0];
        }
        if (isset($ds[1])) {
            $hms = explode(":", $ds[1]);
            if (isset($hms[0])) {
                $dt[3] = $hms[0];
            }
            if (isset($hms[1])) {
                $dt[4] = $hms[1];
            }
            if (isset($hms[2])) {
                $dt[5] = $hms[2];
            }
        }
        foreach ($dt as $k => $v) {
            $v = preg_replace("/^0{1,}/", "", trim($v));
            if ($v == "") {
                $dt[$k] = 0;
            }
        }
        $mt = @mktime($dt[3], $dt[4], $dt[5], $dt[1], $dt[2], $dt[0]);

        return ($mt > 0) ? $mt : time();
    }

    /**
     * Returns a nicely formatted date string for given Datetime string.
     *
     * @param string $dateString Datetime string
     * @param int $format Format of returned date
     * @return string Formatted date string
     */
    public static function nice($dateString = null, $format = 'D, M jS Y, H:i')
    {
        $date = ($dateString == null) ? time() : strtotime($dateString);

        return date($format, $date);
    }

    /**
     * Returns a formatted descriptive date string for given datetime string.
     *
     * If the given date is today, the returned string could be "Today, 6:54 pm".
     * If the given date was yesterday, the returned string could be "Yesterday, 6:54 pm".
     * If $dateString's year is the current year, the returned string does not
     * include mention of the year.
     *
     * @param string $dateString Datetime string or Unix timestamp
     * @return string Described, relative date string
     */
    public static function niceShort($dateString = null)
    {
        $date = ($dateString == null) ? time() : strtotime($dateString);

        $y = (self::isThisYear($date)) ? '' : ' Y';

        if (self::isToday($date)) {
            $ret = sprintf('Today, %s', date("g:i a", $date));
        } elseif (self::wasYesterday($date)) {
            $ret = sprintf('Yesterday, %s', date("g:i a", $date));
        } else {
            $ret = date("M jS{$y}, H:i", $date);
        }

        return $ret;
    }

    /**
     * Returns true if given date is today.
     *
     * @param string $date Unix timestamp
     * @return boolean True if date is today
     */
    public static function isToday($date)
    {
        return date('Y-m-d', $date) == date('Y-m-d', time());
    }

    /**
     * Returns true if given date was yesterday
     *
     * @param string $date Unix timestamp
     * @return boolean True if date was yesterday
     */
    public static function wasYesterday($date)
    {
        return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
    }

    /**
     * Returns true if given date is in this year
     *
     * @param string $date Unix timestamp
     * @return boolean True if date is in this year
     */
    public static function isThisYear($date)
    {
        return date('Y', $date) == date('Y', time());
    }

    /**
     * Returns true if given date is in this week
     *
     * @param string $date Unix timestamp
     * @return boolean True if date is in this week
     */
    public static function isThisWeek($date)
    {
        return date('W Y', $date) == date('W Y', time());
    }

    /**
     * Returns true if given date is in this month
     *
     * @param string $date Unix timestamp
     * @return boolean True if date is in this month
     */
    public static function isThisMonth($date)
    {
        return date('m Y', $date) == date('m Y', time());
    }

    /**
     * Returns either a relative date or a formatted date depending
     * on the difference between the current time and given datetime.
     * $datetime should be in a <i>strtotime</i>-parsable format, like MySQL's datetime datatype.
     *
     * Options:
     *  'format' => a fall back format if the relative time is longer than the duration specified by end
     *  'end' =>  The end of relative time telling
     *
     * Relative dates look something like this:
     * 	3 weeks, 4 days ago
     * 	15 seconds ago
     * Formatted dates look like this:
     * 	on 02/18/2004
     *
     * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
     * like 'Posted ' before the function output.
     *
     * @param string $dateString Datetime string
     * @param array $options Default format if timestamp is used in $dateString
     * @return string Relative time string.
     */
    function timeAgoInWords($dateTime, $options = array())
    {
        $now = time();

        $inSeconds = strtotime($dateTime);
        $backwards = ($inSeconds > $now);

        $format = 'j/n/y';
        $end = '+1 month';

        if (is_array($options)) {
            if (isset($options['format'])) {
                $format = $options['format'];
                unset($options['format']);
            }
            if (isset($options['end'])) {
                $end = $options['end'];
                unset($options['end']);
            }
        } else {
            $format = $options;
        }

        if ($backwards) {
            $futureTime = $inSeconds;
            $pastTime = $now;
        } else {
            $futureTime = $now;
            $pastTime = $inSeconds;
        }
        $diff = $futureTime - $pastTime;

        // If more than a week, then take into account the length of months
        if ($diff >= 604800) {
            $current = array();
            $date = array();

            list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

            list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
            $years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

            if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
                $months = 0;
                $years = 0;
            } else {
                if ($future['Y'] == $past['Y']) {
                    $months = $future['m'] - $past['m'];
                } else {
                    $years = $future['Y'] - $past['Y'];
                    $months = $future['m'] + ((12 * $years) - $past['m']);

                    if ($months >= 12) {
                        $years = floor($months / 12);
                        $months = $months - ($years * 12);
                    }

                    if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
                        $years--;
                    }
                }
            }

            if ($future['d'] >= $past['d']) {
                $days = $future['d'] - $past['d'];
            } else {
                $daysInPastMonth = date('t', $pastTime);
                $daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

                if (!$backwards) {
                    $days = ($daysInPastMonth - $past['d']) + $future['d'];
                } else {
                    $days = ($daysInFutureMonth - $past['d']) + $future['d'];
                }

                if ($future['m'] != $past['m']) {
                    $months--;
                }
            }

            if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
                $months = 11;
                $years--;
            }

            if ($months >= 12) {
                $years = $years + 1;
                $months = $months - 12;
            }

            if ($days >= 7) {
                $weeks = floor($days / 7);
                $days = $days - ($weeks * 7);
            }
        } else {
            $years = $months = $weeks = 0;
            $days = floor($diff / 86400);

            $diff = $diff - ($days * 86400);

            $hours = floor($diff / 3600);
            $diff = $diff - ($hours * 3600);

            $minutes = floor($diff / 60);
            $diff = $diff - ($minutes * 60);
            $seconds = $diff;
        }
        $relativeDate = '';
        $diff = $futureTime - $pastTime;

        if ($diff > abs($now - strtotime($end))) {
            $relativeDate = sprintf('on %s', date($format, $inSeconds));
        } else {
            if ($years > 0) {
                // years and months and days
                $relativeDate .= ( $relativeDate ? ', ' : '') . $years . ' ' . ($years == 1 ? 'year' : 'years');
                $relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') . $months . ' ' . ($months == 1 ? 'month' : 'months') : '';
                $relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks == 1 ? 'week' : 'weeks') : '';
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days == 1 ? 'day' : 'days') : '';
            } elseif (abs($months) > 0) {
                // months, weeks and days
                $relativeDate .= ( $relativeDate ? ', ' : '') . $months . ' ' . ($months == 1 ? 'month' : 'months');
                $relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks == 1 ? 'week' : 'weeks') : '';
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days == 1 ? 'day' : 'days') : '';
            } elseif (abs($weeks) > 0) {
                // weeks and days
                $relativeDate .= ( $relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks == 1 ? 'week' : 'weeks');
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days == 1 ? 'day' : 'days') : '';
            } elseif (abs($days) > 0) {
                // days and hours
                $relativeDate .= ( $relativeDate ? ', ' : '') . $days . ' ' . ($days == 1 ? 'day' : 'days');
                $relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . $hours . ' ' . ($hours == 1 ? 'hour' : 'hours') : '';
            } elseif (abs($hours) > 0) {
                // hours and minutes
                $relativeDate .= ( $relativeDate ? ', ' : '') . $hours . ' ' . ($hours == 1 ? 'hour' : 'hours');
                $relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . $minutes . ' ' . ($minutes == 1 ? 'minute' : 'minutes') : '';
            } elseif (abs($minutes) > 0) {
                // minutes only
                $relativeDate .= ( $relativeDate ? ', ' : '') . $minutes . ' ' . ($minutes == 1 ? 'minute' : 'minutes');
            } else {
                // seconds only
                $relativeDate .= ( $relativeDate ? ', ' : '') . $seconds . ' ' . ($seconds == 1 ? 'second' : 'seconds');
            }

            if (!$backwards) {
                $relativeDate = sprintf('%s ago', $relativeDate);
            }
        }
        return $relativeDate;
    }

    /**
     * 计算出给出的日期是星期几
     * @param <datetime> $date
     * @return <string>
     */
    public static function getWeekDay($date)
    {
        $dateArr = explode("-", $date);
        return date("w", mktime(0, 0, 0, $dateArr[1], $dateArr[2], $dateArr[0]));
    }

    /**
     * 计算两个日期之间的相差天数
     * @param <integer> $begin 开始日期
     * @param <integer> $end 结束日期
     * @return <integer>
     */
    public static function getDifferentDays($begin, $end)
    {
        return (mktime(0, 0, 0, substr($end, 5, 2), substr($end, 8, 2), substr($end, 0, 4)) - mktime(0, 0, 0, substr($begin, 5, 2), substr($begin, 8, 2), substr($begin, 0, 4))) / (3600 * 24);
    }

    /**
     * 增加指定的时间
     * @param <string> $interval
     * @param <integer> $number
     * @param <datetime> $date
     * @return <integer>
     */
    public static function dateAdd($interval, $number, $date)
    {
        $date_time_array = getdate($date);
        $hours = $date_time_array["hours"];
        $minutes = $date_time_array["minutes"];
        $seconds = $date_time_array["seconds"];
        $month = $date_time_array["mon"];
        $day = $date_time_array["mday"];
        $year = $date_time_array["year"];
        switch ($interval) {
            case "yyyy": $year += $number;
                break;
            case "q": $month += ( $number * 3);
                break;
            case "m": $month += $number;
                break;
            case "y":
            case "d":
            case "w": $day += $number;
                break;
            case "ww": $day += ( $number * 7);
                break;
            case "h": $hours += $number;
                break;
            case "n": $minutes += $number;
                break;
            case "s": $seconds += $number;
                break;
        }
        $timestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
        return $timestamp;
    }

    /**
     * 时间显示美化函数
     * @param integer $time
     * @param integer $since
     * @return string
     */
    public static function ago($time, $since = null)
    {
        $patterns = [
            'seconds' => 'less than a minute',
            'minute' => 'about a minute',
            'minutes' => 'dateTimeHelper', '%d minutes',
            'hour' => 'dateTimeHelper', 'about an hour',
            'hours' => 'dateTimeHelper', 'about %d hours',
            'day' => 'dateTimeHelper', 'a day',
            'days' => 'dateTimeHelper', '%d days',
            'month' => 'dateTimeHelper', 'about a month',
            'months' => 'dateTimeHelper', '%d months',
            'year' => 'dateTimeHelper', 'about a year',
            'years' => 'dateTimeHelper', '%d years',
        ];
        if ($since === null) {
            $since = time();
        }
        if (!is_int($since) && !ctype_digit($time)) {
            $since = strtotime($since);
        }
        if (!is_int($time) && !ctype_digit($time)) {
            $time = strtotime($time);
        }
        $seconds = abs($since - $time);
        $minutes = $seconds / 60;
        $hours = $minutes / 60;
        $days = $hours / 24;
        $weeks = $days / 7;
        $months = $days / 30;
        $years = $days / 365;

        if ($seconds < 45) {
            $words = $patterns['seconds'];
        } elseif ($seconds < 90) {
            $words = $patterns['minute'];
        } else if ($minutes < 45) {
            $words = sprintf($patterns['minutes'], $minutes);
        } else if ($minutes < 90) {
            $words = $patterns['hour'];
        } else if ($hours < 24) {
            $words = sprintf($patterns['hours'], $hours);
        } else if ($hours < 48) {
            $words = $patterns['day'];
        } else if ($days < 30) {
            $words = sprintf($patterns['days'], $days);
        } else if ($days < 60) {
            $words = $patterns['month'];
        } else if ($days < 365) {
            $words = sprintf($patterns['months'], $months);
        } else if ($years < 2) {
            $words = $patterns['year'];
        } else {
            $words = sprintf($patterns['years'], $years);
        }
        $suffix = $since - $time > 0 ? 'ago' : '';
        if ($since - $time > 0) {
            return $words . 'ago';
        } else {
            return $words;
        }
    }

    /**
     * 返回今天的日期范围时间戳
     * @return array
     */
    public static function getTodayRange($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        $begin = mktime(0, 0, 0, date("m", $date), date("d", $date), date("Y", $date));
        $end = mktime(23, 59, 59, date("m", $date), date("d", $date), date("Y", $date));

        return [$begin, $end];
    }

    /**
     * 返回周日期范围时间戳
     * @return array
     */
    public static function weekRange($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        $begin = mktime(0, 0, 0, date("m", $date), date("d", $date) - date("w", $date) + 1, date("Y", $date));
        $end = mktime(23, 59, 59, date("m", $date), date("d", $date) - date("w", $date) + 7, date("Y", $date));

        return [$begin, $end];
    }

    /**
     * 返回月日期范围时间戳
     * @return array
     */
    public static function monthRange($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        $begin = mktime(0, 0, 0, date("m", $date), 1, date("Y", $date));
        $end = mktime(23, 59, 59, date("m", $date), date("t", $date), date("Y", $date));

        return [$begin, $end];
    }

    /**
     * 返回季度日期范围时间戳
     * @return array
     */
    public static function quarterRange($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        $getMonthDays = date("t", mktime(0, 0, 0, date('n', $date) + (date('n', $date) - 1) % 3, 1, date("Y", $date)));
        $begin = mktime(0, 0, 0, date('n', $date) - (date('n', $date) - 1) % 3, 1, date('Y', $date));
        $end = mktime(23, 59, 59, date('n', $date) + (date('n', $date) - 1) % 3, $getMonthDays, date('Y', $date));

        return [$begin, $end];
    }

    /**
     * 返回年度日期范围时间戳
     * @return array
     */
    public static function yearRange($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        $year = date('Y', $date);

        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(0, 0, 0, 12, 31, $year)
        ];
    }

    // 减少月份处理，返回正确的年月值
    public static function decreaseMonths($yearMonth, $months = 1)
    {
        $year = (int) substr($yearMonth, 0, 4);
        $month = (int) substr($yearMonth, 4, 2);
        $y = floor($months / 12);
        $year = $year - $y;
        $months = $months % 12;
        $tmp = intval($month) - intval($months);
        if ($tmp == 0) {
            return --$year . '12';
        } elseif ($tmp > 0) {
            return $year . sprintf("%02d", $tmp);
        } else {
            --$year;
            $month = (12 + $tmp);

            return $year . sprintf("%02d", abs($month));
        }
    }

    // 增加月份处理，返回正确的年月值
    public static function increaseMonths($yearMonth, $months)
    {
        $y = substr($yearMonth, 0, 4);
        $m = substr($yearMonth, 4, 2);
        $tmp = intval($m) + intval($months);
        $m = fmod($tmp, 12);
        $y = $y + ($tmp / 12);
        $y = intval($y);
        if ($m == 0) {
            $y--;
            $m = 12;
        }

        return $y . sprintf("%02d", $m);
    }

    /**
     * 两个年月之间的差额
     * 比如：201001 - 200909 之间相差 4 个月
     * @param <integer> $beginYearMonth
     * @param <integer> $endYearMonth
     */
    public static function diffMonths($beginYearMonth, $endYearMonth)
    {
        $years = substr($endYearMonth, 0, 4) - substr($beginYearMonth, 0, 4);
        $months = substr($endYearMonth, 4, 2) - substr($beginYearMonth, 4, 2) + 1;

        return ($years) ? ($years * 12) + $months : $months;
    }

    /**
     * 获取起始日期范围
     * @param integer $beginDate 开始日期
     * @param integer $endDate 结束日期
     * @return array
     */
    public static function toArray($beginDate, $endDate)
    {
        if ($beginDate == $endDate) {
            $data = [$beginDate];
        } else {
            $data = array();
            $diff = self::diffMonths($beginDate, $endDate);
            for ($i = 0; $i < $diff; $i++) {
                $data[] = self::increaseMonths($beginDate, $i);
            }
        }

        return $data;
    }

    /**
     * 将数字转换为有效的日期格式文本
     * 
     * @param integer $number
     * @return string|mixed
     */
    public static function number2Date($number)
    {
        $date = null;
        if (is_numeric($number)) {
            $l = strlen($number);
            switch ($l) {
                case 4: // 2016 => 2016-01-01
                    $date = "$number-01-01";
                    break;

                case 6: // 201601 => 2016-01-01
                case 8:// 20160101 => 2016-01-01
                    $month = (int) substr($number, 4, 2);
                    if ($month >= 1 && $month <= 12) {
                        $date = substr($number, 0, 4) . '-' . sprintf('%02d', $month);
                        if ($l == 6) {
                            $date .= '-01';
                        } else {
                            $day = (int) substr($number, 6, 2);
                            if ($day == 0 || $day > 31) {
                                $date = null;
                            } else {
                                $date .= '-' . sprintf('%02d', $day);
                                $date = date('Y-m-d', self::mktime($date)) == $date ? $date : null;
                            }
                        }
                    }
                    break;

                default:
                    $date = null;
            }
        }

        return $date;
    }

}
