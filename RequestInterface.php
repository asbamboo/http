<?php
namespace asbamboo\http;

use Psr\Http\Message\RequestInterface AS BaseRequestInterface;

/**
 * 继承遵守psr规则的RequestInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface RequestInterface extends BaseRequestInterface{}
