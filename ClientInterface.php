<?php
namespace asbamboo\http;

/**
 *
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月8日
 */
interface ClientInterface
{
    /**
     *  发起请求
     *
     * @param RequestInterface $Request
     * @return ResponseInterface
     */
    public function send(RequestInterface $Request) : ResponseInterface;
}