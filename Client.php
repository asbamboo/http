<?php
namespace asbamboo\http;

use asbamboo\http\exception\ClientException;
use asbamboo\event\EventScheduler;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月10日
 */
class Client implements ClientInterface
{
    /**
     * @var $Response
     */
    private $Response;

    /**
     *
     * @var resource
     */
    private $curl;

    /**
     * - 初始化响应实例
     * - 初始化curl客户端资源
     */
    public function __construct()
    {
        $this->Response = new Response(new Stream('php://temp', 'w+b'));
        $this->curl     = curl_init();
    }

    /**
     * - 关闭curl客户端
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ClientInterface::send()
     */
    public function send(RequestInterface $Request) : ResponseInterface
    {
        curl_reset($this->curl);

        curl_setopt($this->curl, CURLOPT_URL, (string) $Request->getUri());
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $Request->getMethod());
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, $this->getCurloptHttpVersion($Request));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getCurloptHttpHeader($Request));
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, $this->getCurloptHeaderFunction());
        curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, $this->getCurloptWriteFunction());

        if($Request->getUri()->getUserInfo()){
            curl_setopt($this->curl, CURLOPT_USERPWD, $Request->getUri()->getUserInfo());
        }

        $this->setBodyCurlOpt($Request);

        EventScheduler::instance()->trigger(Event::HTTP_CLIENT_SEND_PRE_EXEC, [$this, $this->curl, $Request, $this->Response]);
        curl_exec($this->curl);
        EventScheduler::instance()->trigger(Event::HTTP_CLIENT_SEND_AFTER_EXEC, [$this, $this->curl, $Request, $this->Response]);

        if(curl_errno($this->curl)){
            throw new ClientException('请求失败:' . curl_error($this->curl));
        }

        $this->Response->getBody()->rewind();

        return $this->Response;
    }

    /**
     * 设置body相关的curl option
     *
     * @param RequestInterface $Request
     */
    private function setBodyCurlOpt(RequestInterface $Request) : void
    {

        /*
         * Some HTTP methods cannot have payload:
         *
         * - GET — cURL will automatically change method to PUT or POST if we set CURLOPT_UPLOAD or
         *   CURLOPT_POSTFIELDS.
         * - HEAD — cURL treats HEAD as GET request with a same restrictions.
         * - TRACE — According to RFC7231: a client MUST NOT send a message body in a TRACE request.
         */
        if(!in_array($Request->getMethod(), [Constant::METHOD_GET, Constant::METHOD_HEAD, Constant::METHOD_TRACE], true)){
            $Body       = $Request->getBody();
            $body_size  = $Body->getSize();
            if($body_size !== 0){
                if($Body->isSeekable()){
                    $Body->rewind();
                }

                // Message has non empty body.
                if(null === $body_size || $body_size > 1024 * 1024){
                    // Avoid full loading large or unknown size body into memory
                    curl_setopt($this->curl, CURLOPT_UPLOAD, true);
                    if(null !== $body_size){
                        curl_setopt($this->curl, CURLOPT_INFILESIZE, $body_size);
                    }
                    curl_setopt($this->curl, CURLOPT_READFUNCTION, function($ch, $fd, $length)use($Body){
                        return $Body->read($Body);
                    });
                }else{
                    // Small body can be loaded into memory
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, (string) $Body);
                }
            }
        }

        if($Request->getMethod() === Constant::METHOD_HEAD){
            // This will set HTTP method to "HEAD".
            curl_setopt($this->curl, CURLOPT_NOBODY, true);
        }
    }

    /**
     * 返回curl option CURLOPT_HTTPHEADER
     *
     * @param RequestInterface $Request
     * @return array
     */
    private function getCurloptHttpHeader(RequestInterface $Request) : array
    {
        $options    = [];
        $headers    = $Request->getHeaders();
        foreach ($headers as $name => $values) {
            $header = strtolower($name);
            if ('expect' === $header) {
                // curl-client does not support "Expect-Continue", so dropping "expect" headers
                continue;
            }
            foreach ($values as $value) {
                $options[] = $name . ': ' . $value;
            }
        }
        /*
         * curl-client does not support "Expect-Continue", but cURL adds "Expect" header by default.
         * We can not suppress it, but we can set it to empty.
         */
        $options[] = 'Expect:';

        return $options;
    }

    /**
     * 返回curl option CURLOPT_HTTP_VERSION
     *
     * @param RequestInterface $Request
     * @return int
     */
    private function getCurloptHttpVersion(RequestInterface $Request) : int
    {
        switch($Request->getProtocolVersion()){
            case '1.0':
                return CURL_HTTP_VERSION_1_0;
            case '1.1':
                return CURL_HTTP_VERSION_1_1;
            case '2.0':
                return CURL_HTTP_VERSION_2_0;
        }
        return CURL_HTTP_VERSION_NONE;
    }

    /**
     * 返回curl option CURLOPT_HEADERFUNCTION
     *
     * @return callable
     */
    private function getCurloptHeaderFunction() : callable
    {
        return function($ch, $data){
            $str = trim($data);
            if('' !== $str){
                if(strpos(strtolower($str), 'http/') === 0){
                    $status_line                                        = $str;
                    @list($http_version, $http_code, $reason_phrase)    = explode(' ', $status_line, 3);
                    $this->Response = $this->Response->withStatus((int) $http_code, (string) $reason_phrase);
                    $this->Response = $this->Response->withProtocolVersion((string) $http_version);
                }else{
                    $header_line            = $str;
                    @list($name, $value)    = explode(':', $header_line, 2);
                    $this->Response         = $this->Response->withAddedHeader(trim((string) $name), trim((string) $value));
                }
            }
            return strlen($data);
        };
    }

    /**
     * 返回curl option CURLOPT_WRITEFUNCTION
     *
     * @return callable
     */
    private function getCurloptWriteFunction() : callable
    {
        return function($ch, $data){
            return $this->Response->getBody()->write($data);
        };
    }
}