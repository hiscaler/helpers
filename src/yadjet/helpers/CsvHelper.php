<?php

namespace yadjet\helpers;

/**
 * CSV Helper
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class CsvHelper
{

    public static function readAll($filename, $debug = false)
    {
        $csv = new Csv($debug);

        return $csv->open($filename)
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
            ->AddTitle($title)
            ->addRows($rows)
            ->write()
            ->getFilename();
    }

    public static function send(array $rows, array $title = null, $filename = null, $debug = false)
    {
        $csv = new Csv($debug);

        $csv->open($filename)
            ->AddTitle($title)
            ->addRows($rows)
            ->send();
    }

}
