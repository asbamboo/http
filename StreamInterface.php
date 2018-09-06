<?php
namespace asbamboo\http;

use Psr\Http\Message\StreamInterface AS BaseStreamInterface;

/**
 * 继承遵守psr规则的StreamInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface StreamInterface extends BaseStreamInterface{}
