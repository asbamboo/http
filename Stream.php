<?php
namespace asbamboo\http;

use asbamboo\http\psr\Stream AS BaseStream;

/**
 * 实现StreamInterface[根据psr7]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class Stream extends BaseStream implements StreamInterface{}