<?php
namespace asbamboo\http;

use asbamboo\http\psr\Request AS BaseRequest;

/**
 * 实现 RequestInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Request extends BaseRequest implements MessageInterface, RequestInterface{}
