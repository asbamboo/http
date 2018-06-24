<?php
namespace asbamboo\http;

use asbamboo\http\psr\PsrRequestInterface;

/**
 * 继承遵守psr规则的RequestInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface RequestInterface extends MessageInterface,PsrRequestInterface
{
    /**
     * HTTP METHOD
     *
     * @var string
     */
    const METHOD_HEAD    = 'HEAD';
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_PURGE   = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE   = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';
}
