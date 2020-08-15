<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class IsHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class IpHelperTest extends TestCase
{

    public function testV426()
    {
        $this->assertEquals('0000:0000:0000:0000:0000:ffff:7f00:0001', IpHelper::v426('127.0.0.1'));
    }

    public function testV624()
    {
        $this->assertEquals('127.0.0.1', IpHelper::v624('0000:0000:0000:0000:0000:ffff:7f00:0001'));
    }

}
