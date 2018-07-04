<?php
namespace asbamboo\http\_test\response;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Response;
use asbamboo\http\response\JsonResponse;

class JsonResponseTest extends TestCase
{
    public function testMain()
    {
        $resposne = new JsonResponse('test');
        $this->assertTrue(true);
    }
}