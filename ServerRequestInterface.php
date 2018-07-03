<?php
namespace asbamboo\http;

use asbamboo\http\psr\PsrServerRequestInterface;

/**
 * 继承遵守psr规则的ServerRequestInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface ServerRequestInterface extends RequestInterface,PsrServerRequestInterface
{
    /**
     * 获取request参数
     *
     * 获取客户机发送到服务器的的参数
     *
     * 数据必须与$_REQUEST的结构兼容。
     */
    public function getRequestParams(): array;

    /**
     * 获取[self::getRequestParams()]中的一个key
     *
     * @return string $key 参数的键名
     * @return mixed $default 当参数的键名不存在时返回的默认值
     */
    public function getRequestParam(string $key, $default = null);
}