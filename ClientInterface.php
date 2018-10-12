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

    /**
     * 设置一些选项信息
     *
     * @param array $option
     * @return ClientInterface
     */
    public function setOption(array $option = []) : ClientInterface;

    /**
     * 返回选项信息
     *
     * @param array $option
     * @return ClientInterface
     */
    public function getOption() : array;
}