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
    /**
     * 跳转目标
     *
     * @var string
     */
    private $target_uri;

    /**
     *
     * @param string $target_uri
     * @param int $status_code
     * @param string $version
     */
    public function __construct(string $target_uri, int $status_code = self::STATUS_FOUND, string $version = '1.1')
    {
        $this->setTargetUri($target_uri);
        $this->status_code  = $status_code;
        $this->version      = $version;
    }

    /**
     * 设置跳转目标
     *
     * @param string $target_uri
     * @return self
     */
    public function setTargetUri(string $target_uri) : self
    {
        $this->target_uri           = $target_uri;
        $this->headers['location']  = [$target_uri];
        $this->Body                 = new Stream('php://temp', 'w+b');
        $this->Body->write(sprintf('
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="refresh" content="0;url=%1$s" />

                    <title>Redirecting to %1$s</title>
                </head>
                <body>
                    Redirecting to <a href="%1$s">%1$s</a>.
                </body>
            </html>',
            htmlspecialchars($target_uri, ENT_QUOTES, 'UTF-8'))
        );
        return $this;
    }


}