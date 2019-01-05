<?php
namespace asbamboo\http;

use Psr\Http\Message\ServerRequestInterface AS BaseServerRequestInterface;

/**
 * 继承遵守psr规则的ServerRequestInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface ServerRequestInterface extends BaseServerRequestInterface
{
    /**
     * 获取客户端的ipv4地址
     *
     * @return string
     */
    public function getClientIp() : string;

    /**
     * 返回这个请求是不是通过ajax方式请求
     *
     * @return bool
     */
    public function isAjaxRequest() : bool;

    /**
     * 获取[self::getCookieParams()]中的一个key
     *
     * @return string $key 参数的键名
     * @return mixed $default 当参数的键名不存在时返回的默认值
     */
    public function getCookieParam(string $key, $default = null);

    /**
     * 获取[self::getQueryParams()]中的一个key
     *
     * @return string $key 参数的键名
     * @return mixed $default 当参数的键名不存在时返回的默认值
     */
    public function getQueryParam(string $key, $default = null);

    /**
     * 获取request参数
     *
     * 获取客户机发送到服务器的的参数
     *
     * 数据必须与$_REQUEST的结构兼容。
     */
    public function getRequestParams() : ?array;

    /**
     * 获取[self::getRequestParams()]中的一个key
     *
     * @return string $key 参数的键名
     * @return mixed $default 当参数的键名不存在时返回的默认值
     */
    public function getRequestParam(string $key, $default = null);

    /**
     * 获取post参数
     *
     * 获取客户机发送到服务器的的参数
     *
     * 数据必须与$_POST的结构兼容。
     */
    public function getPostParams() : ?array;

    /**
     * 获取[self::getPostParams()]中的一个key
     *
     * @return string $key 参数的键名
     * @return mixed $default 当参数的键名不存在时返回的默认值
     */
    public function getPostParam(string $key, $default = null);
}
