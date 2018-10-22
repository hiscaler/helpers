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

    public function testTaoBaoIpHelper()
    {
        $ipHelper = (new IpHelper(TaoBaoIpHelper::class, '85.159.145.165'));
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getCountryId(), 'IT');
    }

    public function testCZ88IpHelper()
    {
        $ipHelper = (new IpHelper(CZ88IpHelper::class, '140.205.172.5'));
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getCountryName(), '中国');
        $this->assertEquals($ip->getProvinceName(), '浙江');
    }

}