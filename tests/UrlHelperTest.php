<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class UrlHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class UrlHelperTest extends TestCase
{

    public function testScheme()
    {
        $this->assertEquals(UrlHelper::scheme('http://www.example.com'), 'http');
        $this->assertEquals(UrlHelper::scheme('https://www.example.com'), 'https');
        $this->assertEquals(UrlHelper::scheme('//www.example.com'), '');
        $this->assertEquals(UrlHelper::scheme('//www.example.com', 'http'), 'http');
        $this->assertEquals(UrlHelper::scheme('www.example.com'), '');
        $this->assertEquals(UrlHelper::scheme('www.example.com', 'http'), 'http');
    }

    public function testHost()
    {
        $this->assertEquals(UrlHelper::host('http://www.example.com'), 'www.example.com');
        $this->assertEquals(UrlHelper::host('https://www.example.com'), 'www.example.com');
        $this->assertEquals(UrlHelper::host('//www.example.com'), 'www.example.com');
        $this->assertEquals(UrlHelper::host('www.example.com'), 'www.example.com');
        $this->assertEquals(UrlHelper::host('example.com'), 'example.com');
        $this->assertEquals(UrlHelper::host('example.com/a/b/index.html'), 'example.com');
        $this->assertEquals(UrlHelper::host('example.com/index.html'), 'example.com');
    }

    public function testPort()
    {
        $this->assertEquals(UrlHelper::port('http://www.example.com'), null);
        $this->assertEquals(UrlHelper::port('https://www.example.com'), null);
        $this->assertEquals(UrlHelper::port('//www.example.com:80'), '80');
        $this->assertEquals(UrlHelper::port('www.example.com:80'), 80);
    }

    public function testFindQueryValueByKey()
    {
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2', 'a'), '1');
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2', 'A'), '1');
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?A=1&b=2', 'a'), '1');
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'aa'), null);
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'aa', '1'), '1');
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'c'), '');
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'c', 'cc'), 'cc');
    }

    public function testQueries()
    {
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2'), ['a' => '1', 'b' => '2']);
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c'), ['a' => '1', 'b' => '2', 'c' => '']);
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', ['d' => 5]), ['a' => '1', 'b' => '2', 'c' => '', 'd' => 5]);
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', ['a' => '11']), ['a' => '1', 'b' => '2', 'c' => '']);
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', ['c' => 'cc']), ['a' => '1', 'b' => '2', 'c' => 'cc']);
    }

}
