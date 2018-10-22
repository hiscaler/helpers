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
        $ipHelper = new IpHelper();
        $ipHelper->setEndpoint(TaoBaoIpHelper::class)->setIp('85.159.145.165');
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getCountryId(), 'IT');
    }

    public function testCZ88IpHelper()
    {
        $ipHelper = (new IpHelper())->setIp('140.205.172.5')->setEndpoint(CZ88IpHelper::class);
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getSuccess(), true);
        $this->assertEquals($ip->getCountryName(), '中国');
        $this->assertEquals($ip->getProvinceName(), '浙江');
    }

    public function testFailed()
    {
        $ipHelper = (new IpHelper())->setIp('1')->setEndpoint(CZ88IpHelper::class);
        $ip = $ipHelper->detect();
        $this->assertEquals($ip->getSuccess(), false);
    }

}