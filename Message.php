<?php
namespace asbamboo\http;

use asbamboo\http\psr\Message AS BaseMessage;
use asbamboo\http\traits\MessageTrait;

/**
 * 实现MessageInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class Message extends BaseMessage implements MessageInterface{}