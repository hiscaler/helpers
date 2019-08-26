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
class IpLocationHelperTest extends TestCase
{

    public function testTaobaoIpLocationHelper()
    {
        $ipHelper = new IpLocationHelper();
        $ipHelper->setEndpoint(TaobaoIpLocationHelper::class)->setIp('85.159.145.165');
        $ip = $ipHelper->detect();
        if ($ip->getSuccess()) {
            $this->assertEquals('IT', $ip->getCountryId());
        } else {
            $ip = $ipHelper->setEndpoint(CZ88IpLocationHelper::class)->detect();
            $this->assertEquals('意大利', $ip->getCountryName());
        }
    }

    public function testCZ88IpLocationHelper()
    {
        $ipHelper = new IpLocationHelper();
        $ipHelper->setIp('140.205.172.5')->setEndpoint(get_class(new CZ88IpLocationHelper()));
        $ip = $ipHelper->detect();
        $this->assertEquals(true, $ip->getSuccess());
        $this->assertEquals('中国', $ip->getCountryName());
        $this->assertEquals('浙江', $ip->getProvinceName());
    }

    public function testPcOnlineIpLocationHelper()
    {
        $ipHelper = new IpLocationHelper();
        $ipHelper->setIp('59.42.52.174')->setEndpoint(get_class(new PcOnlineIpLocationHelper()));
        $ip = $ipHelper->detect();
        $this->assertEquals(true, $ip->getSuccess());
        $this->assertEquals('广东省', $ip->getProvinceName());
        $this->assertEquals('广州市', $ip->getCityName());
    }

    public function testMisc()
    {
        $ipHelper = new IpLocationHelper();
        $ip = $ipHelper->setIp('59.42.52.174')->setEndpoint(array(
            get_class(new CZ88IpLocationHelper()),
            get_class(new TaobaoIpLocationHelper()),
            get_class(new PcOnlineIpLocationHelper()),
        ))->detect();
        $this->assertEquals(true, $ip->getSuccess());
        $this->assertEquals('广东', mb_substr($ip->getProvinceName(), 0, 2));
    }

    public function testFailed()
    {
        $ipHelper = new IpLocationHelper();
        $ipHelper->setIp('1')->setEndpoint(get_class(new CZ88IpLocationHelper()));
        $ip = $ipHelper->detect();
        $this->assertEquals(false, $ip->getSuccess());
    }

}