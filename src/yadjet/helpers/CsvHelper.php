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
     * @return array|false
     */
    public static function readAll($filename, array $rowKeys = array(), $removeTitle = false)
    {
        $csv = new Csv();

        return $csv->open($filename)
            ->setRowKeys($rowKeys)
            ->setRemoveTitle($removeTitle)
            ->readAll();
    }

    /**
     * 文件写入
     *
     * @param array $rows
     * @param array|null $title
     * @param null $filename
     * @return mixed
     */
    public static function write(array $rows, array $title = null, $filename = null)
    {
        $csv = new Csv();

        return $csv->open($filename)
            ->setTitle($title)
            ->addRows($rows)
            ->write()
            ->getFilename();
    }

    /**
     * 文件下载
     *
     * @param array $rows
     * @param array|null $title
     * @param null $filename
     */
    public static function download(array $rows, array $title = null, $filename = null)
    {
        $csv = new Csv();

        $csv->open()
            ->setTitle($title)
            ->addRows($rows)
            ->download($filename);
    }

}
