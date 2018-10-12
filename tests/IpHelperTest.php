<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class IpHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class IpHelperTest extends TestCase
{

    public function testRes()
    {
        $ipHelper = (new IpHelper(TaoBaoIpHelper::class, '85.159.145.165'));
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getCountryId(), 'IT');
    }
}