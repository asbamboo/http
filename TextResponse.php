<?php
namespace asbamboo\http;

/**
 * 返回text/html响应
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月15日
 */
class TextResponse extends Response
{
    /**
     *
     * @param array $data
     * @param int $status_code
     * @param string $version
     */
    public function __construct($text, int $status_code = Constant::STATUS_OK, string $version = '1.1')
    {
        $this->status_code  = $status_code;
        $this->version      = $version;
        $this->addHeader('content-type', 'text/html');
        $this->setBody(new Stream('php://temp', 'w+b'))->getBody()->write($text);
        $this->getBody()->rewind();
    }
}