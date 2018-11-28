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
     * @see \asbamboo\http\ServerRequestInterface::getClientIp()
     */
    public function getClientIp() : string
    {
        $forwarded_ips  = [];
        if($this->hasHeader('FORWARDED')){
            $forwarded_header   = $this->getHeaderLine('FORWARDED');
            preg_match_all('@(for)=("?\[?)([a-z0-9\.:_\-/]*)@', $forwarded_header, $matches);
            $forwarded_ips  = $matches[3];
        }else if($this->hasHeader('X_FORWARDED_FOR')){
            $forwarded_ips  = array_map('trim', $this->getHeader('X_FORWARDED_FOR'));
        }

        if(!empty($forwarded_ips)){
            $forwarded_ips      = array_reverse($forwarded_ips);
            foreach($forwarded_ips AS $ip){
                // Remove port (unfortunately, it does happen)
                if(preg_match('@((?:\d+\.){3}\d+)\:\d+@', $ip, $match)) {
                    $ip  = $match[1];
                }
                if(filter_var($ip, FILTER_VALIDATE_IP)){
                    return $ip;
                }
            }
        }

        return $this->getServerParams()['REMOTE_ADDR'];
    }

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