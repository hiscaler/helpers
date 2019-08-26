<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class CsvHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class CsvHelperTest extends TestCase
{

    private $filename;

    public function setUp()
    {
        parent::setUp();
        $this->filename = __DIR__ . '/tmp.csv';
    }

    public function testFilename()
    {
        $filename = CsvHelper::write(array(
            array('a1', 'b1', 'c1'),
            array('a2', 'b2', 'c2'),
        ), array("A", "B", "C"), $this->filename);
        $this->assertEquals($filename, $this->filename);
    }

    public function testWriteLargeFile()
    {
        $rows = array();
        for ($i = 0; $i < 199999; $i++) {
            $rows[] = array("单元格A{$i}的内容", "单元格B{$i}的内容", "单元格C{$i}的内容");
        }
        $filename = CsvHelper::write($rows, array("A", "B", "C"), $this->filename, true);
        $this->assertEquals($filename, $this->filename);
    }

    public function testReadAll()
    {
        $filename = CsvHelper::write(array(
            array('a1', 'b1', 'c1'),
            array('a2', 'b2', 'c2'),
        ), array("A", "B", "C"), $this->filename);
        $lines = CsvHelper::readAll($filename);
        $this->assertSame(array(
            array('A', 'B', 'C'),
            array('a1', 'b1', 'c1'),
            array('a2', 'b2', 'c2'),
        ), $lines);
        $lines = CsvHelper::readAll($filename, array("Title A", "Title B", "Title C"));
        $this->assertSame(array(
            array('Title A' => 'A', 'Title B' => 'B', 'Title C' => 'C'),
            array('Title A' => 'a1', 'Title B' => 'b1', 'Title C' => 'c1'),
            array('Title A' => 'a2', 'Title B' => 'b2', 'Title C' => 'c2'),
        ), $lines);
        $lines = CsvHelper::readAll($filename, array("Title A", "Title B", "Title C"), true);
        $this->assertSame(array(
            array('Title A' => 'a1', 'Title B' => 'b1', 'Title C' => 'c1'),
            array('Title A' => 'a2', 'Title B' => 'b2', 'Title C' => 'c2'),
        ), $lines);
    }

    public function tearDown()
    {
        parent::tearDown();
        file_exists($this->filename) && unlink($this->filename);
    }

}
