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
        $this->assertEquals($this->filename, $filename);
    }

    public function testWriteLargeFile()
    {
        $rows = array();
        for ($i = 0; $i < 199999; $i++) {
            $rows[] = array("单元格A{$i}的内容", "单元格B{$i}的内容", "单元格C{$i}的内容");
        }
        $filename = CsvHelper::write($rows, array("A", "B", "C"), $this->filename, true);
        $this->assertEquals($this->filename, $filename);
    }

    public function testRead()
    {
        $filename = CsvHelper::write(array(
            array('a1', 'b1', 'c1'),
            array('a2', 'b2', 'c2'),
        ), array("A", "B", "C"), $this->filename);
        $lines = CsvHelper::readAll($filename);
        $this->assertSame($lines, array(
            array('A', 'B', 'C'),
            array('a1', 'b1', 'c1'),
            array('a2', 'b2', 'c2'),
        ));
    }

    public function tearDown()
    {
        parent::tearDown();
        file_exists($this->filename) && unlink($this->filename);
    }

}
