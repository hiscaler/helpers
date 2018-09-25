<?php

namespace yadjet\helpers;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testRemoveEmpty()
    {
        $a = ['', ' ', ' '];
        ArrayHelper::removeEmpty($a);
        $this->assertEquals(count($a), 0);
    }

}
