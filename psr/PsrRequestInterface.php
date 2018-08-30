<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * 表示一个传出的客户端请求。
 *
 * 根据HTTP规范，该接口包括下列各属性:
 * - 协议版本[Protocol version]
 * - HTTP方法[HTTP method]
 * - URI
 * - 消息头[Headers]
 * - 消息体[Message body]
 *
 * 在构建过程中，如果没有提供Host header，实现必须尝试从提供的URI中设置Host header。
 *
 * 请求被认为是不可改变的;所有可能更改状态的方法都必须实现，这样它们才能保留当前消息的内部状态，并返回包含已更改状态的实例。\
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface PsrRequestInterface extends PsrMessageInterface
{
    /**
     * 获取消息的请求目标。
     *
     * 获取消息的请求-目标(对于客户端)，因为它出现在请求(对于服务器)，或者是为实例指定的(请参阅withRequestTarget())。
     *
     * 在大多数情况下，这将是组合URI的原始形式，除非提供给具体实现的值(参见下面的withRequestTarget())。
     *
     * 如果没有可用的URI，并且没有特别提供请求目标，则该方法必须返回字符串“/”。
     *
     * @return string
     */
    public function getRequestTarget() : string;

    /**
     * 用特定的request-target返回一个实例。
     *
     * 如果请求需要一个非origin-form的request-target-例如，用于指定absolute-form、authority-form或asterisk-form-该方法可用于创建具有指定的request-target的实例。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回一个具有更改请求目标的实例。
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (对于请求消息中允许的各种request-target表单。)
     * @param string $target
     * @return static
     */
    public function withRequestTarget(string $target) : self;

    /**
     * 获取请求的HTTP方法。
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * 使用提供的HTTP方法返回一个实例。
     *
     * 虽然HTTP方法名称通常都是大写字符，但是HTTP方法名称是区分大小写的，因此实现不应该修改给定的字符串。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更改请求方法的实例。
     *
     * @param string $method 不区分大小写.
     * @return static
     * @throws \InvalidArgumentException 无效的HTTP方法时抛出异常.
     */
    public function withMethod(string $method) : self;

    /**
     * 获取URI实例。
     *
     * 该方法必须返回一个UriInterface实例。
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return PsrUriInterface 返回表示请求URI的URI接口实例。
     */
    public function getUri() : PsrUriInterface;

    /**
     * 用提供的URI返回一个实例。
     *
     * 如果URI包含Host header，则此方法必须在默认情况下更新返回请求的Host header。如果URI不包含Host header，则必须将预先存在的Host header传递到返回的请求。
     *
     * 您可以通过设置“$preserve_host”到“true”来选择保存主机头的原始状态。当“$preserve_host”设置为“true”时，此方法将以下列方式与主机头交互:
     * - 如果没有Host header或是empty，而新URI包含host组件，则此方法必须在返回的请求中更新Host header
     * - 如果没有Host header或是empty，且新URI不包含host组件，则此方法不能在返回的请求中更新Host header
     * - 如果Host header存在且非空，则此方法不能在返回的请求中更新Host header。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有新的UriInterface实例的实例。
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param PsrUriInterface $Uri 使用新的请求URI。
     * @param bool $preserve_host 是否保存Host header的原始状态。
     * @return static
     */
    public function withUri(PsrUriInterface $Uri, bool $preserve_host = false) : self;
}
