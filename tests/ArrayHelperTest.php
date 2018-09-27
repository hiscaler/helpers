<?php

namespace yadjet\helpers;
require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class ArrayHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class ArrayHelperTest extends TestCase
{

    public function testRemoveEmpty()
    {
        $a = ['', ' ', ' ', 'a', '1', 0, '0'];
        ArrayHelper::removeEmpty($a);
        $this->assertEquals(count($a), 4);
    }

}
