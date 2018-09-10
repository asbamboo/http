<?php
namespace asbamboo\http\psr;

use asbamboo\http\psr\traits\MessageTrait;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * 实现MessageInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class Message implements MessageInterface
{
    use MessageTrait;

    /**
     *
     * @param StreamInterface $Body
     * @param array $headers
     * @param string $version
     */
    public function __construct(StreamInterface $Body, array $headers = []/*[]*/, string $version = '1.1')
    {
        $this->version  = $version;
        $this->Body     = $Body;
        $this->headers  = $headers;
    }
}
