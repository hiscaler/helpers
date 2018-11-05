<?php

namespace yadjet\helpers;

require __DIR__ . '/../vendor/autoload.php';

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
        $this->assertEquals(ImageHelper::getExtension('https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=1020953036,4179630328&fm=173&s=C7802BE60863AE846F31447903001052&w=640&h=380&img.a'), 'jpg');
        $this->assertEquals(ImageHelper::getExtension('https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=1020953036,4179630328&fm=173&s=C7802BE60863AE846F31447903001052&w=640&h=380&img.png'), 'jpg');
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
