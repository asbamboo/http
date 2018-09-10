<?php
namespace asbamboo\http;

use asbamboo\http\psr\ServerRequest AS BaseServerRequest;

/**
 * 实现ServerRequestInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class ServerRequest extends BaseServerRequest implements ServerRequestInterface
{

    /**
     *
     * @var array
     */
    protected $requests;

    /**
     *
     * @var array
     */
    protected $posts;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getCookieParam()
     */
    public function getCookieParam(string $key, $default = null)
    {
        return $this->getCookieParams()[$key] ?? $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getQueryParam()
     */
    public function getQueryParam(string $key, $default = null)
    {
        return $this->getQueryParams()[$key] ?? $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getRequestParams()
     */
    public function getRequestParams() : ?array
    {
        if(is_null($this->requests)){
            $this->requests   = $_REQUEST;
        }
        return $this->requests;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getRequestParam()
     */
    public function getRequestParam(string $key, $default = null)
    {
        return $this->getRequestParams()[$key] ?? $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getPostParams()
     */
    public function getPostParams() : ?array
    {
        if(is_null($this->posts)){
            $this->posts   = $_POST;
        }
        return $this->posts;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ServerRequestInterface::getPostParam()
     */
    public function getPostParam(string $key, $default = null)
    {
        return $this->getPostParams()[$key] ?? $default;
    }
}