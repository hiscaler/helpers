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

    public function open($filename = null)
    {
        if ($filename == null) {
            $filename = tempnam(sys_get_temp_dir(), 'csv');
        }
        $this->filename = $filename;
        $this->file = fopen($filename, 'w+');
        fputs($this->file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        return $this;
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

