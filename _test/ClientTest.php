<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Client;
use asbamboo\http\Request;
use asbamboo\http\psr\Uri;
use asbamboo\http\ResponseInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月10日
 */
class ClientTest extends TestCase
{
    public function testSend()
    {
        $Client     = new Client();
        $Request    = new Request(new Uri('http://example.org'));
        $Response   = $Client->send($Request);


//         var_dump($Response);
//         var_dump($Response->getHeaders());
//         var_dump((string) $Response->getBody());

        $this->assertInstanceOf(ResponseInterface::class, $Response);
    }
}