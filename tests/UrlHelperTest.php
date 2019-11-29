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
        $this->assertEquals('http', UrlHelper::scheme('http://www.example.com'));
        $this->assertEquals('https', UrlHelper::scheme('https://www.example.com'));
        $this->assertEquals('', UrlHelper::scheme('//www.example.com'));
        $this->assertEquals('http', UrlHelper::scheme('//www.example.com', 'http'));
        $this->assertEquals('', UrlHelper::scheme('www.example.com'));
        $this->assertEquals('http', UrlHelper::scheme('www.example.com', 'http'));
    }

    public function testHost()
    {
        $this->assertEquals('www.example.com', UrlHelper::host('http://www.example.com'));
        $this->assertEquals("www.example.com", UrlHelper::host('https://www.example.com'));
        $this->assertEquals("www.example.com", UrlHelper::host('//www.example.com'));
        $this->assertEquals("www.example.com", UrlHelper::host('www.example.com'));
        $this->assertEquals("example.com", UrlHelper::host('example.com'));
        $this->assertEquals("example.com", UrlHelper::host('example.com/a/b/index.html'));
        $this->assertEquals("example.com", UrlHelper::host('example.com/index.html'));
    }

    public function testPort()
    {
        $this->assertEquals(null, UrlHelper::port('http://www.example.com'));
        $this->assertEquals(null, UrlHelper::port('https://www.example.com'));
        $this->assertEquals(80, UrlHelper::port('//www.example.com:80'));
        $this->assertEquals(80, UrlHelper::port('www.example.com:80'));
    }

    public function testFindQueryValueByKey()
    {
        $this->assertEquals('1', UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2', 'a'));
        $this->assertEquals('1', UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1', 'a'));
        $this->assertEquals('1', UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2', 'A'));
        $this->assertEquals('1', UrlHelper::findQueryValueByKey('http://www.example.com/a.html?A=1&b=2', 'a'));
        $this->assertEquals(null, UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'aa'));
        $this->assertEquals(null, UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'aa'));
        $this->assertEquals('', UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'c'));
        $this->assertEquals(null, UrlHelper::findQueryValueByKey('http://www.example.com/a.html?a=1&b=2&c', 'cc'));
    }

    public function testQueries()
    {
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2'), array('a' => '1', 'b' => '2'));
        $this->assertSame(UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c'), array('a' => '1', 'b' => '2', 'c' => ''));
        $this->assertSame(array('a' => '1', 'b' => '2', 'c' => '', 'd' => 5), UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', array('d' => 5)));
        $this->assertSame(array('a' => '1', 'b' => '2', 'c' => ''), UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', array('a' => '11')));
        $this->assertSame(array('a' => '1', 'b' => '2', 'c' => 'cc'), UrlHelper::queries('http://www.example.com/a.html?a=1&b=2&c', array('c' => 'cc')));
    }

    public function testAddQueryParam()
    {
        $this->assertEquals('http://www.example.com?b=bb', UrlHelper::addQueryParam('http://www.example.com', 'b', 'bb'));
        $this->assertEquals('http://www.example.com/a.html?a=1&b=2', UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&b=2', 'b', 'bb'));
        $this->assertEquals('http://www.example.com/a.html?a=1&b=bb', UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&b=2', 'b', 'bb', false));
        $this->assertEquals('http://www.example.com/a.html?a=1&bb=2&b=bb', UrlHelper::addQueryParam('http://www.example.com/a.html?a=1&bb=2', 'b', 'bb', false));
        $this->assertEquals('http://www.example.com/a.html?a=v&bb=2', UrlHelper::addQueryParam('http://www.example.com/a.html?a=%A4%A7&bb=2', 'a', 'v', false));
        $this->assertEquals('http://www.example.com/a.html?a=v&bb=2&=b', UrlHelper::addQueryParam('http://www.example.com/a.html?a=%A4%A7&bb=2&=b', 'a', 'v', false));
        $this->assertEquals('http://www.example.com/a.html?a=China&bb=2&=b', UrlHelper::addQueryParam('http://www.example.com/a.html?a=中国&bb=2&=b', 'a', 'China', false));
        $this->assertEquals('http://www.example.com/a.html?aa=CN&a=China&=b', UrlHelper::addQueryParam('http://www.example.com/a.html?aa=CN&a=CN&=b', 'a', 'China', false));
        $this->assertEquals('http://www.example.com/a.html?aa=CN&a=China&=b&1&0=1', UrlHelper::addQueryParam('http://www.example.com/a.html?aa=CN&a=CN&=b&1&0=1', 'a', 'China', false));
        $this->assertEquals('http://www.example.com/register?type=register&openid=1111', UrlHelper::addQueryParam('http://www.example.com/register?type=register', 'openid', '1111'));
    }

    public function testDecode()
    {
        // Normal
        $this->assertEquals("http://www.example.com", UrlHelper::decode('http://www.example.com'));
        $this->assertEquals("http://www.example.com?q=%", UrlHelper::decode('http://www.example.com?q=%'));
        $this->assertEquals("http://www.example.com?q=%a", UrlHelper::decode('http://www.example.com?q=%a'));
        $this->assertEquals("http://www.example.com?a=1&b=2&c&d=", UrlHelper::decode('http://www.example.com?a=1&b=2&c&d='));
        $this->assertEquals("http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1", UrlHelper::decode('http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'));
        // Javascript encodeURI
        $this->assertEquals("http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1", UrlHelper::decode('http://www.example.coms?q=%E5%AD%A6%E4%B9%A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'));
        $this->assertEquals("http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1", UrlHelper::decode('http://www.example.coms?q=%E5%AD%A6%E4%B9%A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1', 2));
        // 编码一次
        $this->assertEquals("http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1", UrlHelper::decode('http://www.example.coms?q=%25E5%25AD%25A6%25E4%25B9%25A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'));
        // 编码两次
        $this->assertEquals("http://www.example.coms?q=学习&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1", UrlHelper::decode('http://www.example.coms?q=%2525E5%2525AD%2525A6%2525E4%2525B9%2525A0&uc_param_str=dnntnwvepffrgibijbprsvdsme&from=wh20010&uc_sm=1'));
        $this->assertEquals("http://www.example.com/bj/?span=gz-ya10bjbhbjtjc&unit=y-a-zmb-yjbl6.23&key=没戮么", UrlHelper::decode('http://www.example.com/bj/?span=%EF%BF%BD%EF%BF%BDgz-y%EF%BF%BD%EF%BF%BDa10bjbhbjtjc&unit=y-a-zmb-yjbl6.23&key=%C3%BB%EF%BF%BD%EF%BF%BD%EF%BF%BD%C2%BE%EF%BF%BD%EF%BF%BD%EF%BF%BD%C3%B4%EF%BF%BD%EF%BF%BD'));
        $this->assertEquals("http://www.example.com/yjbt/?plan=【gz-l】yjbd&unit=zqyjbd&key=经期时间长", UrlHelper::decode('http://www.example.com/yjbt/?plan=%A1%BEgz-l%A1%BFyjbd&unit=zqyjbd&key=%BE%AD%C6%DA%CA%B1%BC%E4%B3%A4'));
    }

    public function testIsAbsolute()
    {
        $this->assertEquals(true, UrlHelper::isAbsolute('http://www.example.com'));
        $this->assertEquals(true, UrlHelper::isAbsolute('http://www.example.com?id=1'));
        $this->assertEquals(true, UrlHelper::isAbsolute('//www.example.com?id=1'));
        $this->assertEquals(false, UrlHelper::isAbsolute('/list?id=1'));
    }

    public function testIsRelative()
    {
        $this->assertEquals(false, UrlHelper::isRelative('http://www.example.com'));
        $this->assertEquals(false, UrlHelper::isRelative('http://www.example.com?id=1'));
        $this->assertEquals(false, UrlHelper::isRelative('//www.example.com?id=1'));
        $this->assertEquals(true, UrlHelper::isRelative('/list?id=1'));
    }

}
