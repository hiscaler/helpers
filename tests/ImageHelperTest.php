<?php

namespace yadjet\helpers;

require '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/**
 * Class ImageHelperTest
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
class ImageHelperTest extends TestCase
{

    public function testGetExtension()
    {
        $this->assertEquals(ImageHelper::getExtension('http://www.example.com/images/image/124/12460449.jpg?t=1537200428#a'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('http://www.example.com/images/image/124/12460449.jpg'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('https://www.example.com/images/image/124/12460449.jpg?t=1537200428#a'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('//www.example.com/images/image/124/12460449.jpg?t=1537200428#a'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('12460449.jpg'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('12460449.jpg?t=1537200428#a'), 'jpg');
    }

    public function testFullPath()
    {
        $this->assertEquals(ImageHelper::fullPath('/images/image/124/12460449.jpg', 'http://www.example.com'), 'http://www.example.com/images/image/124/12460449.jpg');
        $this->assertEquals(ImageHelper::fullPath('http://www.example.com/images/image/124/12460449.jpg', ''), 'http://www.example.com/images/image/124/12460449.jpg');
        $this->assertEquals(ImageHelper::fullPath('http://www.example.com/images/image/124/12460449.jpg', 'http://www.example.com'), 'http://www.example.com/images/image/124/12460449.jpg');
    }

    public function testParseImages()
    {
        $html = <<<EOT
<div>
<img src="a.jpg"/>
<p>Hello</p>
<img src="http://www.example.com/a.jpg"/>
<h1>World!</h1>
<img src="http://www.example.com/a.jpg?t=123456"/>
< Img
</div>
EOT;
        $this->assertSame(ImageHelper::parseImages($html), [
            'a.jpg',
            'http://www.example.com/a.jpg',
            'http://www.example.com/a.jpg?t=123456',
        ]);
    }
}
