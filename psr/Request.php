<?php
namespace asbamboo\http\psr;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use asbamboo\http\psr\traits\RequestTrait;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\MessageInterface;
use asbamboo\http\Constant;

/**
 * 实现 RequestInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Request implements MessageInterface, RequestInterface
{
    use RequestTrait;

    /**
     *
     * @param UriInterface $Uri
     * @param string $method
     * @param array $headers
     * @param string $version
     */
    public function __construct(UriInterface $Uri, StreamInterface $Body = null, string $method = Constant::METHOD_GET, array $headers = []/*[]*/, string $version = '1.1')
    {
        $this->Uri      = $Uri;
        $this->Body     = $Body ?? new Stream('php://temp', 'wb+');
        $this->headers  = $headers;
        $this->version  = $version;
        $this->method   = $method;
    }
}
