<?php
namespace asbamboo\http;

/**
 * 事件名列表
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月10日
 */
final class Event
{
    // client实例的send方法执行curl_exec之前触发该事件
    const HTTP_CLIENT_SEND_PRE_EXEC     = 'http_client_send_pre_curl_exec';

    // client实例的send方法执行curl_exec之后触发该事件
    const HTTP_CLIENT_SEND_AFTER_EXEC   = 'http_client_send_after_curl_exec';
}