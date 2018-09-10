<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Uri;
use asbamboo\http\Request;
use asbamboo\http\Constant;

/**
 * Test Request
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class RequestTest extends TestCase
{
    public function testGetRequestTarget()
    {
        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri);
        $this->assertEquals('/path?arg1=value1&arg2=value2#anchor', $Request->getRequestTarget());

        $Uri        = new Uri("http://username:password@hostname");
        $Request    = new Request($Uri);
        $this->assertEquals('/', $Request->getRequestTarget());

        $New        = $Request->withRequestTarget('/path');
        $this->assertEquals('/', $Request->getRequestTarget());
        $this->assertEquals('/path', $New->getRequestTarget());
    }

    public function testGetMethod()
    {
        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri);
        $this->assertEquals(Constant::METHOD_GET, $Request->getMethod());

        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri, null, Constant::METHOD_POST);
        $this->assertEquals(Constant::METHOD_POST, $Request->getMethod());
    }

    public function testGetUri()
    {
        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
    }

    public function testWithUri()
    {
        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri);

        $Uri        = new Uri("/path?arg1=value1&arg2=value2#anchor");
        $New        = $Request->withUri($Uri);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
        $this->assertEquals('/path?arg1=value1&arg2=value2#anchor', $New->getUri());
        $this->assertEquals([], $New->getHeader('host'));

        $Uri        = new Uri("/path?arg1=value1&arg2=value2#anchor");
        $New       = $Request->withUri($Uri, true);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
        $this->assertEquals('/path?arg1=value1&arg2=value2#anchor', $New->getUri());
        $this->assertEquals([], $New->getHeader('host'));

        $Uri        = new Uri("http://username:password@hostname2/path?arg1=value1&arg2=value2#anchor");
        $New        = $Request->withUri($Uri);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
        $this->assertEquals('http://username:password@hostname2/path?arg1=value1&arg2=value2#anchor', $New->getUri());
        $this->assertEquals(['hostname2'], $New->getHeader('host'));

        $Uri        = new Uri("https://username:password@hostname2:443/path?arg1=value1&arg2=value2#anchor");
        $New        = $Request->withUri($Uri);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
        $this->assertEquals('https://username:password@hostname2:443/path?arg1=value1&arg2=value2#anchor', $New->getUri());
        $this->assertEquals(['hostname2:443'], $New->getHeader('host'));


        $Uri        = new Uri("https://username:password@hostname2:443/path?arg1=value1&arg2=value2#anchor");
        $New        = $Request->withUri($Uri, true);
        $this->assertEquals('http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor', $Request->getUri());
        $this->assertEquals('https://username:password@hostname2:443/path?arg1=value1&arg2=value2#anchor', $New->getUri());
        $this->assertEquals([], $New->getHeader('host'));
    }

    public function testWithRequestTarget()
    {
        $Uri        = new Uri("http://username:password@hostname");
        $Request    = new Request($Uri);
        $New        = $Request->withRequestTarget('/path');
        $this->assertEquals('/', $Request->getRequestTarget());
        $this->assertEquals('/path', $New->getRequestTarget());

        $New        = $Request->withRequestTarget('');
        $this->assertEquals('/', $New->getRequestTarget());
    }

    public function testWithMethod()
    {
        $Uri        = new Uri("http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor");
        $Request    = new Request($Uri);
        $New        = $Request->withMethod(Constant::METHOD_OPTIONS);
        $this->assertEquals(Constant::METHOD_GET, $Request->getMethod());
        $this->assertEquals(Constant::METHOD_OPTIONS, $New->getMethod());
    }
}
