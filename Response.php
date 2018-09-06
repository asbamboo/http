<?php
namespace asbamboo\http;

use asbamboo\http\psr\Response AS BaseResponse;

/**
 * 实现ResponseInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Response extends BaseResponse implements ResponseInterface
{

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::withAddedHeader()
     *
     * @param string $name
     * @param array|string $value
     * @return MessageInterface
     */
    public function addHeader(string $name, /*array|string*/$value) : ResponseInterface
    {
        $name       = strtolower($name);
        $value      = (array) $value;

        $this->headers[$name]   = $this->headers[$name] ?? [];
        $this->headers[$name]   = array_merge($this->headers[$name], $value);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ResponseInterface::setBody()
     */
    public function setBody(StreamInterface $Stream) : ResponseInterface
    {
        $this->Body = $Stream;
        return $this;

    }

    /**
     * 发送header到客户端
     *
     * {@inheritDoc}
     * @see MessageInterface::sendHeaders()
     */
    public function sendHeaders() : void
    {
        header(sprintf('HTTP/%s %s %s', $this->version, $this->status_code, $this->getReasonPhrase()), true, $this->status_code);

        foreach($this->getHeaders() AS $name => $values ){
            foreach((array)$values as $value) {
                header($name.': '.$value, false, $this->status_code);
            }
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::sendBody()
     */
    public function sendBody() : void
    {
        echo $this->getBody();
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::send()
     */
    public function send() : void
    {
        $this->sendHeaders();
        $this->sendBody();
    }
}
