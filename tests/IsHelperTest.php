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
class IsHelperTest extends TestCase
{

    private $base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAASCAYAAABFGc6jAAADw0lEQVQ4jWVVW09cVRT+9uXMYa5cApRpKUgFQmnTqJG0L9YYTSQx9oHH/kE18aFGrTFq4oOpYqKGFqs0tQ2ahooDgYEZZ86Zs49r7X32mUPdyeTs67p837fWiJm199Lm9CR4pGkKIQR9DX2lXdOuPZNSwRhzZs3nfJcH33f7MruHzFaKv1uH0LwYbvIFkT8SggyZyP4GCTunvZQMgY2VoHWJDGskySAL1NDcBcvDO+ShvRPeVIofJTYKpRKcWxRYeC2kb4jahAAlgf4p0PrL4OnPBn/82IFJQ8gs+2GW3lmaB619ej4CpRRF38XyGwrX3gWq4xSVogdZZOUycJGQbl5OMbOksfXFAKeHJWu06MQ5kvmeRmHwIWc0/wpwdT2Crqc47iQ2gEpZU8QCUWwQx0CtVMbi2ggwGODeRz16XC6aymFjztiu9tl4+HhcutGHKg/QISePn7bR7cRYWR5DtRrg+EChe1BDc34FL03P4vJNoNHewzdf/oooEXC5S8sXQ+lFlovBDYfz6FwPiaEnBNn0ZIB+QyIMgYqqY/XCdVxZehuTlSa0DIAx4NXbMdZf38Xdb7/H199tkljOwpZD52XMxBlDYhAx8ZSyANFoCKuZUI9gqXwda6MbqMoJ9HoR1EjAGoVME1xbXcL8xfNon5zgh62dPGjPl/TQsVU/P3pOMjUDGJJtSkYIcVTEKFbKb9F3Av1ejPsPtgnaLvFF822an3YxPtrArXdunqkr5txyNYTOS1Jgd1Mi+jehrBLrkMurKsZR05P2PCgFuHplFeFICEFk87xWr1oryy/P5XAVC1eyR5eezDN6ci/Enz+RoKLYOaPMOPLEOjZUOylKRBrfLs75DLm0h0q2HLH8hvBlh2kD25/GqDfbqEwl6BNvLfUc/1T3ENRrUJmaioNNGIL54c7jM62LpwV5+0JzuCpVQhSFOHom8eyXEHFHoVI7Qdz8BBtv3sbU+Dlo6iJFwRpS6X6rhY8/+wovBs8Qag/bsFlmzmQVO59LJBGnLrm7YVf+hr2dD7Hx/joW5mYJspJ9ZojElBx9cOcu7v/+BKkqZfYcjNxtXmiqjkBLHjXM1GgEgbA1paRrupsPHmHr4SMsLsxh9vwM8WZwTJJeXJjH/mEbyCTtVGysTauDCzdupVMTY7nuvWKG2BORSmVN09VZQm1Hwv1FCCt+5tVYJ6nQVokF9rB/cOQ48hEU27ovYJllOmxR3MW1y5qexYPEQuNYd4j5Ii0GLX3UQ4eeJ/G/zDzEfs2QBlpnYTlfXgjFt/zmP4l3J7wS2RQBAAAAAElFTkSuQmCC';

    public function testImage()
    {
        $this->assertEquals(IsHelper::image('http://www.example.com/images/image/124/12460449.jpg?t=1537200428#a'), false);
        $this->assertEquals(IsHelper::image($this->base64Image), true);
        $this->assertEquals(IsHelper::image('/images/a.jpg'), false);
        $this->assertEquals(IsHelper::image(__DIR__ . '/IsHelperTest.php'), false);
        $this->assertEquals(IsHelper::image(__DIR__ . '/test.png'), true);
    }

    public function testBase64Image()
    {
        $this->assertEquals(IsHelper::image($this->base64Image), true);
    }

    public function testXml()
    {
        $this->assertEquals(IsHelper::xml('<xml><return_code><![CDATA[FAIL]]></return_code>
<return_msg><![CDATA[No Bill Exist]]></return_msg>
<error_code><![CDATA[20002]]></error_code>
</xml>
'), true);
        $this->assertEquals(IsHelper::xml('<xml><return_code><![CDATA[FAIL]]></return_code>
<return_msg><![CDATA[No Bill Exist></return_msg>
<error_code><![CDATA[20002]]></error_code>
</xml>
'), false);
    }

}