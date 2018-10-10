<?php
namespace asbamboo\http;

/**
 * 处理一个 RequestInterface 实例的请求
 * 返回一个 ResponseInterface 实例的响应
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