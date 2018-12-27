<?php

namespace yadjet\helpers;

/**
 * CSV Helper
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class CsvHelper
{

    /**
     * 读取文件所有数据
     *
     * @param $filename
     * @param array $rowKeys
     * @param bool $removeTitle
     * @param bool $debug
     * @return array|false
     */
    public static function readAll($filename, array $rowKeys = array(), $removeTitle = false, $debug = false)
    {
        $csv = new Csv($debug);

        return $csv->open($filename)
            ->setRowKeys($rowKeys)
            ->setRemoveTitle($removeTitle)
            ->readAll();
    }

    /**
     * @param array $rows
     * @param array|null $title
     * @param null $filename
     * @param bool $debug
     * @return mixed
     */
    public static function write(array $rows, array $title = null, $filename = null, $debug = false)
    {
        $csv = new Csv($debug);

        return $csv->open($filename)
            ->setTitle($title)
            ->addRows($rows)
            ->write()
            ->getFilename();
    }

    public static function send(array $rows, array $title = null, $filename = null, $debug = false)
    {
        $csv = new Csv($debug);

        $csv->open($filename)
            ->setTitle($title)
            ->addRows($rows)
            ->send();
    }

}
