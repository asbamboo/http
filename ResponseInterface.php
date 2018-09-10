<?php
namespace asbamboo\http;

use Psr\Http\Message\ResponseInterface AS BaseResponseInterface;

/**
 * 继承遵守psr规则的ResponseInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月19日
 */
interface ResponseInterface extends BaseResponseInterface
{
    /**
     * 添加一个header信息, 与BaseResponseInterface::withAddedHeader的区别是:
     *  - withAddedHeader 返回一个新的response实例
     *  - addHeader 返回当前resposne实例
     *
     * @return ResponseInterface
     */
    public function addHeader(string $name, /*array|string*/$value) : ResponseInterface;

    /**
     * 设置body。与BaseResponseInterface::withBody区别是:
     *  - withBody 返回一个新的response实例
     *  - setBody 返回当前resposne实例
     *
     * @param StreamInterface $Stream
     * @return ResponseInterface
     */
    public function setBody(StreamInterface $Stream) : ResponseInterface;

    /**
     * 发送消息头
     */
    public function sendHeaders() : void;

    /**
     * 发送消息主体
     */
    public function sendBody() : void;

    /**
     * 发送消息头
     * 并且发送消息发送消息主题
     */
    public function send() : void;
}