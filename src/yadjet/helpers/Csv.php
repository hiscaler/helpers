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

    /**
     * @var bool Debug 模式
     */
    private $debug = false;

    /**
     * @var resource 文件句柄
     */
    private $file;

    /**
     * @var string 需要处理的文件
     */
    private $filename;

    /**
     * @var array 标题
     */
    private $title = array();

    /**
     * @var array 内容行
     */
    private $rows = array();

    /**
     * @var array 内容行输出对应的键值
     */
    private $rowKeys = array();

    /**
     * @var bool 是否已经写入
     */
    private $isWritten = false;

    /**
     * @var bool 输出时是否移除标题行
     */
    private $removeTitle = false;

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
            $filename = sys_get_temp_dir() . '/' . uniqid() . ".csv";
        }
        $this->filename = $filename;
        $this->file = fopen($filename, file_exists($filename) ? 'rb' : 'wb');
        fputs($this->file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        return $this;
    }

    /**
     * 设置标题
     *
     * @param array $title
     * @return $this
     */
    public function setTitle(array $title)
    {
        $this->title = $title;

        return $this;
    }

    public function setRowKeys(array $rowKeys)
    {
        $this->rowKeys = $rowKeys;

        return $this;
    }

    /**
     * 是否移除标题
     *
     * @param $remove
     * @return Csv
     */
    public function setRemoveTitle($remove)
    {
        $this->removeTitle = $remove ? true : false;

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
        $i = 1;
        while (!feof($this->file) && ($line = fgetcsv($this->file, 4096)) !== false) {
            if ($this->removeTitle && $i === 1) {
                $i++;
                continue;
            }

            if ($this->rowKeys) {
                $t = array();
                foreach ($line as $key => $value) {
                    if (isset($this->rowKeys[$key])) {
                        $t[$this->rowKeys[$key]] = $value;
                    } else {
                        $t[] = $value;
                    }
                }
                $lines[] = $t;
            } else {
                $lines[] = $line;
            }
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
        $this->title && fputcsv($this->file, $this->title);
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

    /**
     * 文件下载
     *
     * @param string|null $filename
     */
    public function download($filename = null)
    {
        if (!$this->isWritten) {
            $this->write();
        } else {
            $this->close();
        }

        if ($filename) {
            if (($index = strripos($filename, '.')) !== false) {
                if (strtolower(substr($filename, $index)) != '.csv') {
                    $filename = "{$filename}.csv";
                }
            } else {
                $filename = "{$filename}.csv";
            }
        } else {
            $filename = basename($this->filename);
        }

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; CHARSET=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($this->filename));
        readfile($this->filename);
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
