<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\JsonResponse;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月1日
 */
class JsonResponseTest extends TestCase
{
    public function testMain()
    {
        $JsonResponse   = new JsonResponse(['test' => 'value']);
        $this->assertEquals(['test' => 'value'],json_decode($JsonResponse->getBody()->getContents(), true));
        $this->assertEquals(['application/json'], $JsonResponse->getHeader('content-type'));
    }
}
