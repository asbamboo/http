<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Response;
use asbamboo\http\Stream;
use asbamboo\http\Constant;

/**
 * test Response
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class ResponseTest extends TestCase
{
    public function testGetStatusCode()
    {
        $Body       = new Stream('php://temp', 'w+b');
        $Response   = new Response($Body);
        $this->assertEquals(Constant::STATUS_OK, $Response->getStatusCode());

        return $Response;
    }

    /**
     * @depends testGetStatusCode
     */
    public function testGetReasonPhrase(Response $Response)
    {
        $this->assertEquals('OK', $Response->getReasonPhrase());

        return $Response;
    }

    /**
     * @depends testGetStatusCode
     */
    public function testWithStatus(Response $Response)
    {
        $New1   = $Response->withStatus(Constant::STATUS_NO_CONTENT);
        $this->assertEquals(Constant::STATUS_OK, $Response->getStatusCode());
        $this->assertEquals('OK', $Response->getReasonPhrase());
        $this->assertEquals(Constant::STATUS_NO_CONTENT, $New1->getStatusCode());
        $this->assertEquals('No Content', $New1->getReasonPhrase());

        $New2   = $Response->withStatus(Constant::STATUS_NO_CONTENT, '没有内容');
        $this->assertEquals(Constant::STATUS_OK, $Response->getStatusCode());
        $this->assertEquals('OK', $Response->getReasonPhrase());
        $this->assertEquals(Constant::STATUS_NO_CONTENT, $New2->getStatusCode());
        $this->assertEquals('没有内容', $New2->getReasonPhrase());

        return $Response;
    }

    /**
     * @depends testWithStatus
     */
    public function testAddHeader(Response $Response)
    {
        $Response->addHeader('tESt', 'test');
        $this->assertEquals(['test'], $Response->getHeader('test'));
        return $Response;
    }

    /**
     * @depends testAddHeader
     */
    public function testSetBody(Response $Response)
    {
        $Body       = new Stream('php://temp', 'w+b');
        $Response->setBody($Body);
        $this->assertEquals($Body, $Response->getBody());
        return $Response;
    }
}
