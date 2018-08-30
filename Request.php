<?php
namespace asbamboo\http;

use asbamboo\http\traits\RequestTrait;

/**
 * 实现 RequestInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Request implements RequestInterface
{
    use RequestTrait;

    /**
     *
     * @param UriInterface $Uri
     * @param string $method
     * @param array $headers
     * @param string $version
     */
    public function __construct(UriInterface $Uri, StreamInterface $Body = null, string $method = Self::METHOD_GET, array $headers = []/*[]*/, string $version = '1.1')
    {
        $this->Uri      = $Uri;
        $this->Body     = $Body ?? new Stream('php://temp', 'wb+');
        $this->headers  = $headers;
        $this->version  = $version;
        $this->method   = $method;
    }
}
