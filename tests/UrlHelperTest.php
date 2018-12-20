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
        $this->assertEquals(UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1', 'a'), '1');
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

    public function testAddQueryParam()
    {
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com', 'b', 'bb'), 'http://www.example.com?b=bb');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&b=2', 'b', 'bb'), 'http://www.example.com/a.html?a=1&b=2');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&b=2', 'b', 'bb', false), 'http://www.example.com/a.html?a=1&b=bb');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&bb=2', 'b', 'bb', false), 'http://www.example.com/a.html?a=1&bb=2&b=bb');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=%A4%A7&bb=2', 'a', 'v', false), 'http://www.example.com/a.html?a=v&bb=2');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=%A4%A7&bb=2&=b', 'a', 'v', false), 'http://www.example.com/a.html?a=v&bb=2&=b');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?a=中国&bb=2&=b', 'a', 'China', false), 'http://www.example.com/a.html?a=China&bb=2&=b');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?aa=CN&a=CN&=b', 'a', 'China', false), 'http://www.example.com/a.html?aa=CN&a=China&=b');
        $this->assertEquals(UrlHelper::addQueryParam('http://www.example.com/a.html?aa=CN&a=CN&=b&1&0=1', 'a', 'China', false), 'http://www.example.com/a.html?aa=CN&a=China&=b&1&0=1');
    }

    public function testDecode()
    {
        $this->assertEquals(UrlHelper::decode('http://www.example.com'), "http://www.example.com");
        $this->assertEquals(UrlHelper::decode('http://www.example.com?a=1&b=2&c&d='), "http://www.example.com?a=1&b=2&c&d=");
        $this->assertEquals(UrlHelper::decode('http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'), "http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1");
        $this->assertEquals(UrlHelper::decode('http://www.example.coms?q=%E5%AD%A6%E4%B9%A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'), "http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1");
        $this->assertEquals(UrlHelper::decode('http://www.example.coms?q=%E5%AD%A6%E4%B9%A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1', 2), "http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1");
        $this->assertEquals(UrlHelper::decode('http://www.example.coms?q=%25E5%25AD%25A6%25E4%25B9%25A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1', 2), "http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1");
        $this->assertNotEquals(UrlHelper::decode('http://www.example.coms?q=%25E5%25AD%25A6%25E4%25B9%25A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1', 1), "http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1");
    }

    public function testIsAbsolute()
    {
        $this->assertEquals(UrlHelper::isAbsolute('http://www.example.com'), true);
        $this->assertEquals(UrlHelper::isAbsolute('http://www.example.com?id=1'), true);
        $this->assertEquals(UrlHelper::isAbsolute('//www.example.com?id=1'), true);
        $this->assertEquals(UrlHelper::isAbsolute('/list?id=1'), false);
    }

    public function testIsRelative()
    {
        $this->assertEquals(UrlHelper::isRelative('http://www.example.com'), false);
        $this->assertEquals(UrlHelper::isRelative('http://www.example.com?id=1'), false);
        $this->assertEquals(UrlHelper::isRelative('//www.example.com?id=1'), false);
        $this->assertEquals(UrlHelper::isRelative('/list?id=1'), true);
    }

}
