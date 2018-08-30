<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Uri;
use asbamboo\http\exception\NotAllowUriSchemeException;
use asbamboo\http\exception\InvalidUriHostException;

/**
 * test Uri
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月21日
 */
class UriTest extends TestCase
{
    public function getUris()
    {
        yield ["http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor"];
        yield ["//username:password@hostname:80/path?arg1=value1&arg2=value2#anchor"];
        yield ["//hostname:80/path?arg1=value1&arg2=value2#anchor"];
        yield ["//hostname/path?arg1=value1&arg2=value2#anchor"];
        yield ["/path?arg1=value1&arg2=value2#anchor"];
        yield ["/path?arg1=value1&arg2=value2"];
        yield ["/path#anchor"];
        yield ["http://hostname"];
        yield ["aaa://hostname"];
    }

    /**
     * @dataProvider getUris
     */
    public function testGet($uri)
    {
        $Uri        = new Uri($uri);

        $this->assertEquals($uri, $Uri);

        return $Uri;
    }

    public function testWithScheme()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withScheme('https');
        $this->assertEquals('http', $Uri->getScheme());
        $this->assertEquals('https', $NewUri->getScheme());

        $this->expectException(NotAllowUriSchemeException::class);
        $NewUri     = $Uri->withScheme('httpscc');
    }

    public function testWithUserInfo()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withUserInfo('username');
        $this->assertEquals('http://hostname', $Uri);
        $this->assertEquals('http://username@hostname', $NewUri);
        $NewUri     = $Uri->withUserInfo('username', 'password');
        $this->assertEquals('http://username:password@hostname', $NewUri);
    }

    public function testWithHost()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withHost('newhost.test');
        $this->assertEquals('http://hostname', $Uri);
        $this->assertEquals('http://newhost.test', $NewUri);

        $this->expectException(InvalidUriHostException::class);
        $NewUri     = $Uri->withHost('n{}ewhost');
    }

    public function testWithPort()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withPort('8080');
        $this->assertEquals(null, $Uri->getPort());
        $this->assertEquals(8080, $NewUri->getPort());
    }

    public function testWithPath()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withPath('/path');

        $this->assertEquals('', $Uri->getPath());
        $this->assertEquals('/path', $NewUri->getPath());
    }

    public function testWithQuery()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withQuery('arg1=val1');
        $this->assertEquals('', $Uri->getQuery());
        $this->assertEquals('http://hostname?arg1=val1', $NewUri);
    }

    public function testWithFragment()
    {
        $Uri        = new Uri('http://hostname');
        $NewUri     = $Uri->withFragment('a');
        $this->assertEquals('', $Uri->getFragment());
        $this->assertEquals('http://hostname#a', $NewUri);
    }
}