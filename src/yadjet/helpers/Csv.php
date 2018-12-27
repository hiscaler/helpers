<?php

namespace yadjet\helpers;

/**
 * CSV 操作类
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class Csv
{

    private $debug = false;
    private $file;
    private $filename;
    private $rows = array();
    private $isWritten = false;

    public function __construct($debug)
    {
        $this->debug = $debug ? true : false;
    }

    /**
     * 打开需要处理的文件
     *
     * @param null $filename
     * @return $this
     */
    public function open($filename = null)
    {
        if ($filename == null) {
            $filename = tempnam(sys_get_temp_dir(), 'csv');
        }
        $this->filename = $filename;
        $this->file = fopen($filename, file_exists($filename) ? 'rb' : 'wb');
        fputs($this->file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        return $this;
    }

    /**
     * 读取 CSV 内容并且转换为数组
     *
     * @return array|false|null
     */
    public function readAll()
    {
        if (fgets($this->file, 4) !== "\xef\xbb\xbf") {
            rewind($this->file);
        }

        $lines = array();
        while (!feof($this->file) && ($line = fgetcsv($this->file, 4096)) !== false) {
            $lines[] = $line;
        }

        $this->close();

        return $lines;
    }

    /**
     * 从 CSV 文件中读取一行
     *
     * @param $n
     * @return array|false|null
     */
    public function readOne($n)
    {
        if (fgets($this->file, 4) !== "\xef\xbb\xbf") {
            rewind($this->file);
        }

        $row = array();
        $i = 1;
        while (!feof($this->file) && ($line = fgetcsv($this->file, 4096)) !== false) {
            if ($i == $n) {
                $row = $line;
                break;
            }
            $i++;
        }

        $this->close();

        return $row;
    }

    public function AddTitle(array $title)
    {
        $title && $this->rows[] = $title;

        return $this;
    }

    private function addRow(array $row)
    {
        $row && $this->rows[] = $row;

        return $this;
    }

    public function addRows(array $rows)
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }

        return $this;
    }

    public function write()
    {
        foreach ($this->rows as $i => $row) {
            if ($this->debug) {
                echo "Write $i row..." . PHP_EOL;
            }

            fputcsv($this->file, $row);
        }
        $this->isWritten = true;

        $this->close();

        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function send()
    {
        if (!$this->isWritten) {
            $this->write();
        } else {
            $this->close();
        }

        $name = basename($this->filename, '.csv');
        header('Content-Type: text/csv; CHARSET=UTF-8');
        header('Content-Disposition: attachment; filename=' . $name);
        exit(0);
    }

    public function close()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }

        return $this;
    }

}

