<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * 表示输出，服务器端响应。
 *
 * 根据HTTP规范，该接口包括以下各点的属性:
 * - 协议版本[Protocol version]
 * - 状态代码和原因短语。[Status code and reason phrase]
 * - 头[Headers]
 * - 消息体[Message body]
 *
 * 被认为是不可变的反应;所有可能更改状态的方法都必须实现，这样它们才能保留当前消息的内部状态，并返回包含已更改状态的实例。
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月19日
 */
interface PsrResponseInterface extends PsrMessageInterface
{
    /**
     * 获取响应状态代码。
     *
     * 状态代码是服务器试图理解和满足请求的3位整数结果代码。
     *
     * @return int 状态码
     */
    public function getStatusCode() : int;

    /**
     * 返回一个具有指定状态码的实例，并可选地返回原因短语。
     *
     * 如果没有指定原因，则实现可以选择默认为RFC7231或IANA推荐的原因短语作为响应的状态代码。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更新状态和原因短语的实例。
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code 要设置的3位整数结果代码。
     * @param string $reasonPhrase 使用提供的状态代码使用的原因;如果没有提供，则实现可以使用HTTP规范中建议的默认值。
     * @return static
     * @throws \InvalidArgumentException 对于无效的状态码参数。
     */
    public function withStatus(int $code, string $reason_phrase = '') : self;

    /**
     * 获取与状态码关联的响应原因短语。
     *
     * 因为一个原因短语不是响应状态行中的必需元素，故短语值可能为null。实现可以选择返回默认的RFC7231推荐理由语句(或在IANA HTTP状态代码注册表中列出的)作为响应的状态代码。
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string 原因短语;如果没有出现，则必须返回空字符串。
     */
    public function getReasonPhrase() : string;
}
