<?php
namespace asbamboo\http\psr\traits;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
trait RequestTrait
{
    use MessageTrait;

    /**
     *
     * @var UriInterface
     */
    protected $Uri;

    /**
     *
     * @var string
     */
    protected $request_target;

    /**
     *
     * @var string
     */
    protected $method;


    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::getRequestTarget()
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if(is_null($this->request_target)){
            $this->request_target   = $this->Uri->getPath() . ($this->Uri->getQuery() ? '?' : '' );
            $this->request_target   = $this->request_target . $this->Uri->getQuery();
            $this->request_target   = $this->request_target . ($this->Uri->getFragment() ? '#' : '');
            $this->request_target   = $this->request_target . $this->Uri->getFragment();
        }

        return '/' . ltrim($this->request_target, '/');
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::getMethod()
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::getUri()
     *
     * @return UriInterface
     */
    public function getUri() : UriInterface
    {
        return $this->Uri;
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::withUri()
     *
     * @param UriInterface $Uri
     * @param bool $preserve_host
     * @return RequestInterface
     */
    public function withUri(UriInterface $Uri, /*bool*/ $preserve_host = false) : RequestInterface
    {
        $New    = clone $this;
        $New->Uri   = $Uri;

        if($preserve_host == true){
            return $New;
        }

        if($Uri->getHost() == ''){
            return $New;
        }

        $host   = $Uri->getHost() . ($Uri->getPort() ? ':' : '') . $Uri->getPort();
        return  $New->withHeader('host', $host);
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::withRequestTarget()
     *
     * @param string $target
     * @return RequestInterface
     */
    public function withRequestTarget(/*string*/ $target) : RequestInterface
    {
        $New                    = clone $this;
        $New->request_target    = $target;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see RequestInterface::withMethod()
     *
     * @param string $method
     * @return RequestInterface
     */
    public function withMethod(/*string*/ $method): RequestInterface
    {
        $New            = clone $this;
        $New->method    = $method;
        return $New;
    }
}