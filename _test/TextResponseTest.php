<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\TextResponse;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月1日
 */
class TextResponseTest extends TestCase
{
    public function testMain()
    {
        $TextResponse   = new TextResponse('<html></html>');
        $this->assertEquals('<html></html>', $TextResponse->getBody()->getContents());
        $this->assertEquals(['text/html'], $TextResponse->getHeader('content-type'));
    }
}
