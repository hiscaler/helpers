<?php

namespace yadjet\helpers;

/**
 * Array Helper
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class ArrayHelper
{

    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     *
     * @code php
     * $arr = array('', 'test', '   ');
     * ArrayHelper::removeEmpty($arr);
     *
     * dump($arr);
     *   // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr 要处理的数组
     * @param boolean $trim 是否对数组元素调用 trim 函数
     * @param array $charList 希望过滤的字符
     */
    public static function removeEmpty(&$arr, $trim = true, $charList = null)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                self::removeEmpty($arr[$key]);
            } else {
                if ($trim) {
                    $value = trim($value, " \t\n\r\0\x0B{$charList}");
                }
                if ($value === '') {
                    unset($arr[$key]);
                } else {
                    $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     *
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $values = ArrayHelper::cols($rows, 'value');
     *
     * dump($values);
     *   // 输出结果为
     *   // array(
     *   //   '1-1',
     *   //   '2-1',
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $col 要查询的键
     *
     * @return array 包含指定键所有值的数组
     */
    public static function getCols($arr, $col)
    {
        $ret = array();
        foreach ($arr as $row) {
            if (isset($row[$col])) {
                $ret[] = $row[$col];
            }
        }

        return $ret;
    }

    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     *
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = ArrayHelper::hashMap($rows, 'id', 'value');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * @endcode
     *
     * 如果省略 $valueField 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = ArrayHelper::hashMap($rows, 'id');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $keyField 按照什么键的值进行转换
     * @param string $valueField 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    public static function toHashmap($arr, $keyField, $valueField = null)
    {
        $ret = array();
        if ($valueField) {
            foreach ($arr as $row) {
                $ret[$row[$keyField]] = $row[$valueField];
            }
        } else {
            foreach ($arr as $row) {
                $ret[$row[$keyField]] = $row;
            }
        }

        return $ret;
    }

    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     *
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     * $values = ArrayHelper::groupBy($rows, 'parent');
     *
     * dump($values);
     *   // 按照 parent 分组的输出结果为
     *   // array(
     *   //   1 => array(
     *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *   //   ),
     *   //   2 => array(
     *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *   //   ),
     *   //   3 => array(
     *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
     *   //   ),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    public static function groupBy($arr, $key)
    {
        $ret = array();
        foreach ($arr as $row) {
            $ret[$row[$key]][] = $row;
        }

        return $ret;
    }

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     * 用法：
     *
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 0),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 0),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 0),
     *
     *     array('id' => 7, 'value' => '2-1-1', 'parent' => 2),
     *     array('id' => 8, 'value' => '2-1-2', 'parent' => 2),
     *     array('id' => 9, 'value' => '3-1-1', 'parent' => 3),
     *     array('id' => 10, 'value' => '3-1-1-1', 'parent' => 9),
     * );
     *
     * $tree = ArrayHelper::toTree($rows, 'id', 'parent', 'nodes');
     *
     * dump($tree);
     *   // 输出结果为：
     *   // array(
     *   //   array('id' => 1, ..., 'nodes' => array()),
     *   //   array('id' => 2, ..., 'nodes' => array(
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //   ),
     *   //   array('id' => 3, ..., 'nodes' => array(
     *   //        array('id' => 9, ..., 'parent' => 3, 'nodes' => array(
     *   //             array(..., , 'parent' => 9, 'nodes' => array(),
     *   //        ),
     *   //   ),
     *   // )
     * @endcode
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * @code php
     * $refs = null;
     * $tree = ArrayHelper::toTree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * dump($refs[$id]);
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key 节点ID字段名
     * @param string $parentKey 节点父ID字段名
     * @param string $childrenKey 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     * @return array
     */
    public static function toTree($arr, $key, $parentKey = 'parent_id', $childrenKey = 'children', &$refs = null)
    {
        $refs = array();
        foreach ($arr as $offset => $row) {
            $arr[$offset][$childrenKey] = array();
            $refs[$row[$key]] = &$arr[$offset];
        }

        $tree = array();
        foreach ($arr as $offset => $row) {
            $parentId = $row[$parentKey];
            if ($parentId) {
                if (!isset($refs[$parentId])) {
                    $tree[] = &$arr[$offset];
                    continue;
                }
                $parent = &$refs[$parentId];
                $parent[$childrenKey][] = &$arr[$offset];
            } else {
                $tree[] = &$arr[$offset];
            }
        }

        return $tree;
    }

    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $childrenKey 包含子节点的键名
     *
     * @return array 展开后的数组
     */
    public static function treeToArray($tree, $childrenKey = 'children')
    {
        $ret = array();
        foreach ($tree as $item) {
            if (isset($item[$childrenKey]) && is_array($item[$childrenKey])) {
                $children = $item[$childrenKey];
                unset($item[$childrenKey]);
                $ret[] = $item;
                $children && $ret = array_merge($ret, self::treeToArray($children, $childrenKey));
            } else {
                unset($item[$childrenKey]);
                $ret[] = $item;
            }
        }

        return $ret;
    }

    /**
     * 根据指定的键对数组排序
     *
     * 用法：
     *
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     *
     * $rows = ArrayHelper::sortByCol($rows, 'id', SORT_DESC);
     * dump($rows);
     * // 输出结果为：
     * // array(
     * //   array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * //   array('id' => 5, 'value' => '5-1', 'parent' => 2),
     * //   array('id' => 4, 'value' => '4-1', 'parent' => 2),
     * //   array('id' => 3, 'value' => '3-1', 'parent' => 1),
     * //   array('id' => 2, 'value' => '2-1', 'parent' => 1),
     * //   array('id' => 1, 'value' => '1-1', 'parent' => 1),
     * // )
     * @endcode
     *
     * @param array $array 要排序的数组
     * @param string $keyname 排序的键
     * @param int $dir 排序方向
     *
     * @return array 排序后的数组
     */
    public static function sortByCol($array, $keyname, $dir = SORT_ASC)
    {
        return self::sortByMultiCols($array, [$keyname => $dir]);
    }

    /**
     * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
     *
     * 用法：
     *
     * @code php
     * $rows = ArrayHelper::sortByMultiCols($rows, array(
     *     'parent' => SORT_ASC,
     *     'name' => SORT_DESC,
     * ));
     * @endcode
     *
     * @param array $rowset 要排序的数组
     * @param array $args 排序的键
     *
     * @return array 排序后的数组
     */
    public static function sortByMultiCols($rowset, $args)
    {
        $sortArray = array();
        $sortRule = '';
        foreach ($args as $sortField => $sortDir) {
            foreach ($rowset as $offset => $row) {
                $sortArray[$sortField][$offset] = $row[$sortField];
            }
            $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
        }
        if (empty($sortArray) || empty($sortRule)) {
            return $rowset;
        }
        eval('array_multisort(' . $sortRule . '$rowset);');

        return $rowset;
    }

    /**
     * 计算多维数组差集
     *
     * @link http://codereview.stackexchange.com/questions/28098/pure-php-array-diff-assoc-recursive-function From stackexchange.com
     * @param array $a
     * @param array $b
     * @return array
     */
    public static function arrayDiffAssocRecursive($a, $b)
    {
        // Get all of the "compare against" arrays
        $b = array_slice(func_get_args(), 1);
        // Initial return value
        $ret = array();

        // Loop over the "to" array and compare with the others
        foreach ($a as $key => $val) {
            // We should compare type first
            $aType = gettype($val);
            // If it's an array, we recurse, otherwise we just compare with "==="
            $args = $aType === 'array' ? array($val) : true;

            // Let's see what we have to compare to
            foreach ($b as $x) {
                // If the key doesn't exist or the type is different,
                // then it's different, and our work here is done
                if (!array_key_exists($key, $x) || $aType !== gettype($x[$key])) {
                    $ret[$key] = $val;
                    continue 2;
                }

                // If we are working with arrays, then we recurse
                if ($aType === 'array') {
                    $args[] = $x[$key];
                } else {
                    // Otherwise we just compare
                    $args = $args && $val === $x[$key];
                }
            }

            // This is where we call ourselves with all of the arrays we got passed
            if ($aType === 'array') {
                $comp = call_user_func_array(array(get_called_class(), 'arrayDiffAssocRecursive'), $args);
                // An empty array means we are equal :-)
                if (count($comp) > 0) {
                    $ret[$key] = $comp;
                }
            } elseif (!$args) {
                // If the values don't match, then we found a difference
                $ret[$key] = $val;
            }
        }

        return $ret;
    }

}
