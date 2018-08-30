<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Message;
use asbamboo\http\Stream;

/**
 * Test Message
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class MessageTest extends TestCase
{
    public function testGetProtocolVersion()
    {
        $Body       = new Stream('php://temp', 'w+b');
        $Message    = new Message($Body, []);
        $this->assertEquals('1.1', $Message->getProtocolVersion());

        $Message    = new Message($Body, [], '1.0');
        $this->assertEquals('1.0', $Message->getProtocolVersion());
    }

    public function testHasHeader()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body, []);
        $this->assertFalse($Message->hasHeader('Content-Type'));

        $Message            = new Message($Body, ['content-type'=>['text/html']]);
        $this->assertTrue($Message->hasHeader('Content-Type'));
    }

    public function testGetHeader()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $this->assertEquals([], $Message->getHeader('Content-Type'));

        $Message            = new Message($Body, ['content-type'=>['text/html']]);
        $this->assertEquals(['text/html'], $Message->getHeader('Content-Type'));
    }

    public function testGetHeaders()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $this->assertEquals([], $Message->getHeaders());

        $Message            = new Message($Body, ['content-type'=>['text/html']]);
        $this->assertEquals(['content-type'=>['text/html']], $Message->getHeaders());
    }

    public function testGetHeaderLine()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $this->assertEquals('', $Message->getHeaderLine('Content-Type'));

        $Message            = new Message($Body, ['content-type'=>['text/html']]);
        $this->assertEquals('text/html', $Message->getHeaderLine('Content-Type'));

        $Message            = new Message($Body, ['content-type'=>['text/html', 'charset:utf-8']]);
        $this->assertEquals('text/html,charset:utf-8', $Message->getHeaderLine('Content-Type'));
    }

    public function testGetBody()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $this->assertEquals('', $Message->getBody());

        $Body->write('test');
        $this->assertEquals('test', $Message->getBody());
    }

    public function testWithProtocolVersion()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $New                = $Message->withProtocolVersion('1.0');
        $this->assertEquals('1.0', $New->getProtocolVersion());
        $this->assertEquals('1.1', $Message->getProtocolVersion());
    }

    public function testWithHeader()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $New                = $Message->withHeader('Content-Type', 'text/html');
        $this->assertEquals(['text/html'], $New->getHeader('content-type'));
        $this->assertEquals([], $Message->getHeader('content-type'));
    }

    public function testWithAddedHeader()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $New                = $Message->withAddedHeader('Content-Type', 'text/html');
        $New2                = $New->withAddedHeader('Content-Type', ['charset:utf-8']);
        $this->assertEquals(['text/html','charset:utf-8'], $New2->getHeader('content-type'));
        $this->assertEquals(['text/html'], $New->getHeader('content-type'));
        $this->assertEquals([], $Message->getHeader('content-type'));
        return $New2;
    }

    /**
     * @depends  testWithAddedHeader
     */
    public function testWithoutHeader($Message)
    {
        $New    = $Message->withoutHeader('content-type');
        $this->assertEquals([], $New->getHeader('content-type'));
        $New2   = $New->withoutHeader('content-type');
        $this->assertEquals([], $New2->getHeader('content-type'));
    }

    public function testWithBody()
    {
        $Body               = new Stream('php://temp', 'w+b');
        $Body2              = new Stream('php://temp', 'w+b');
        $Message            = new Message($Body);
        $New                = $Message->withBody($Body2);
        $Body->write('body');
        $Body2->write('body2');
        $this->assertEquals('body2', $New->getBody());
        $this->assertEquals('body', $Message->getBody());
    }
}
