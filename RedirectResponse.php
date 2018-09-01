<?php
namespace asbamboo\http;

/**
 * RESPONSE 跳转掉另一个url
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月1日
 */
class RedirectResponse extends Response
{
    private $target_uri;

    public function __construct(string $target_uri, int $status_code = self::STATUS_FOUND, string $version = '1.1')
    {
        $this->setTargetUri($target_uri);
        $this->status_code  = $status_code;
        $this->version      = $version;
    }

    public function setTargetUri(string $target_uri) : self
    {
        $this->target_uri           = $target_uri;
        $this->headers['location']  = [$target_uri];

        return $this;
    }
}