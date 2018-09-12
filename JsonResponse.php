<?php
namespace asbamboo\http;

/**
 * 响应json数据
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月1日
 */
class JsonResponse extends Response
{
    /**
     *
     * @param array $data
     * @param int $status_code
     * @param string $version
     */
    public function __construct($data, int $status_code = Constant::STATUS_OK, string $version = '1.1')
    {
        $this->status_code  = $status_code;
        $this->version      = $version;
        $this->addHeader('content-type', 'application/json');
        $this->setBody(new Stream('php://temp', 'w+b'))->getBody()->write(json_encode($data));
        $this->getBody()->rewind();
    }
}