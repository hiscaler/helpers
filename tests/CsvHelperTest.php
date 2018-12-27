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

    public function testFilename()
    {
        $filename = CsvHelper::write();
        echo $filename;
    }

    public function testGetColumn()
    {
        $rows = array(
            array('id' => 1, 'value' => '1-1'),
            array('id' => 2, 'value' => '2-1'),
        );
        $this->assertSame(ArrayHelper::getColumn($rows, 'id'), [1, 2]);
        $this->assertSame(ArrayHelper::getColumn($rows, 'value'), ['1-1', '2-1']);

        $rows = array(
            array('id' => 1, 'children' => ['id' => 11]),
            array('id' => 2, 'children' => ['id' => 22]),
        );
        $this->assertSame(ArrayHelper::getColumn($rows, 'children'), [['id' => 11], ['id' => 22]]);
    }

}
