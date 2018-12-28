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
     * @var resource 文件句柄
     */
    private $fileHandle;

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
        $this->fileHandle = fopen($filename, file_exists($filename) ? 'r+b' : 'w+b');
        fputs($this->fileHandle, chr(0xEF) . chr(0xBB) . chr(0xBF));

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

    /**
     * 设置读取成数组后的键值
     *
     * @param array $rowKeys
     * @return $this
     */
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
        if (fgets($this->fileHandle, 4) !== "\xef\xbb\xbf") {
            rewind($this->fileHandle);
        }

        $lines = array();
        $i = 1;
        while (!feof($this->fileHandle) && ($line = fgetcsv($this->fileHandle, 4096)) !== false) {
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
        if (fgets($this->fileHandle, 4) !== "\xef\xbb\xbf") {
            rewind($this->fileHandle);
        }

        $row = array();
        $i = 1;
        while (!feof($this->fileHandle) && ($line = fgetcsv($this->fileHandle, 4096)) !== false) {
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

    private function writeFile($close = true)
    {
        $this->title && fputcsv($this->fileHandle, $this->title);
        foreach ($this->rows as $i => $row) {
            fputcsv($this->fileHandle, array_map(function ($v) {
                if (is_string($v)) {
                    return $v;
                } elseif (is_array($v)) {
                    return json_encode($v, JSON_UNESCAPED_UNICODE);
                } else {
                    return (string) $v;
                }
            }, $row));
        }
        $this->isWritten = true;

        $close && $this->close();
    }

    public function write()
    {
        $this->writeFile(true);

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
            $this->writeFile(false);
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
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Accept-Ranges: bytes');
        header('Expires: 0');
        header('Content-Length: ' . filesize($this->filename));
        set_time_limit(0);

        $chunkSize = 8 * 1024 * 1024;
        fseek($this->fileHandle, 0);
        while (!feof($this->fileHandle)) {
            echo fread($this->fileHandle, $chunkSize);
            flush();
        }
        $this->close();
        @unlink($this->filename);
        exit(0);
    }

    public function close()
    {
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }

        return $this;
    }

}
