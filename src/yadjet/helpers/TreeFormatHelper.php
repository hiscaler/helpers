<?php

namespace yadjet\helpers;

/**
 * Tree data to single array
 * @author hiscalar <hiscaler@gmail.com>
 */
class TreeFormatHelper {

    /**
     * 把一个Tree形数组转换成一个一维数组，用于方便地显示
     *
     * @param array  $tree 原始Tree形数组
     * @param array  $arr 二维数组
     * @param string $level 目录深度
     * @param string $T 上下均有项目的符号，可以是图片路径
     * @param string $L 这一级别中最末尾项目的符号，可以是图片路径
     * @param string $I 上级连接符，可以是图片路径
     * @param string $S 占位的空白符号，可以是图片路径
     *
     * 类似下面的效果
     * ├- 1
     * │  ├- 1.1
     * │  └- 1.2
     * └- 2
     */
    public static function dumpArrayTree($tree, $level = 0, $T = '┣', $L = '┗', $I = '┃', $S = '...') {
        return self::_makeLevelstr(self::_dumpArrayTree($tree, array(), $level, $T, $L, $I, $S), $T, $L, $I, $S);
    }

    public static function _dumpArrayTree($tree, $arr = array(), $level = 0, $T = '├', $L = '└', $I = '│', $S = '　') {
        foreach ($tree as $node) {
            $arr[] = $node;
            $arr[count($arr) - 1]['level'] = $level;

            //如果存在下级类目，则去掉该键值，并加深一层类目深度
            if (isset($arr[count($arr) - 1]['children'])) {
                unset($arr[count($arr) - 1]['children']);
                $level = $level + 1;
            }

            //如果children仍有数据则递归一下
            if (isset($node['children'])) {
                $arr = self::_dumpArrayTree($node['children'], $arr, $level, $T, $L, $I, $S);
                $level = $level - 1;
            }
        }

        return $arr;
    }

    public static function _makeLevelstr($arr, $t, $l, $i, $s) {

        foreach ($arr as $key => $value) {
            $arr[$key]['levelstr'] = '';

            //向下循环到数组尾部，寻找层特征
            $k = 0;
            $haveBrother = false;
            for ($k = $key; $k < count($arr); $k++) {
                if (isset($arr[$k + 1])) {
                    //有平级目录
                    if ($arr[$key]['level'] == $arr[$k + 1]['level']) {
                        $haveBrother = true;
                    }
                    //本级别结束
                    if ($arr[$key]['level'] > $arr[$k + 1]['level']) {
                        break;
                    }
                }
            }

            if ($haveBrother) {
                $arr[$key]['levelstr'] = $t;
                $arr[$key]['isend'] = false;
            } else {
                $arr[$key]['levelstr'] = $l;
                // isend 为 true 意味着这个节点是本级最后一个节点
                $arr[$key]['isend'] = true;
            }


            // $spaceHere 用来记录连接线的形态，表示当前行第几级是空白
            $spaceHere = array();

            // 逐级向上循环
            for ($k = $key - 1; $k >= 0; $k = $k - 1) {
                //如果$k是同级尾部isend=true
                if ($arr[$k]['isend']) {
                    $spaceHere[$key][$arr[$k]['level']] = true;
                }
                // 判断到根后中断循环
                if ($arr[$k]['level'] == 0) {
                    break;
                }
            }

            //根据级别判定显示连接线的显示
            $frontLine = '';
            for ($j = 0; $j < $value['level']; $j++) {
                if (isset($spaceHere[$key][$j])) {
                    $frontLine .= $s . $s;
                } else {
                    $frontLine .= $i . $s;
                }
            }
            $arr[$key]['levelstr'] = $frontLine . $arr[$key]['levelstr'];
        }

        return $arr;
    }

}
