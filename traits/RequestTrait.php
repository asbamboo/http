<?php
namespace asbamboo\http\traits;

use asbamboo\http\UriInterface;
use asbamboo\http\psr\PsrRequestInterface;
use asbamboo\http\psr\PsrUriInterface;

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
     * @see \asbamboo\http\psr\PsrRequestInterface::getRequestTarget()
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if(is_null($this->request_target)){
            $this->request_target   = $this->Uri->getPath() . ($this->Uri->getQuery() ? '?' : '' ) . $this->Uri->getQuery() . ($this->Uri->getFragment() ? '#' : '') . $this->Uri->getFragment();
        }

        return '/' . ltrim($this->request_target, '/');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrRequestInterface::getMethod()
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
     * @see \asbamboo\http\psr\PsrRequestInterface::getUri()
     *
     * @return PsrUriInterface
     */
    public function getUri() : PsrUriInterface
    {
        return $this->Uri;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrRequestInterface::withUri()
     *
     * @param PsrUriInterface $Uri
     * @param bool $preserve_host
     * @return PsrRequestInterface
     */
    public function withUri(PsrUriInterface $Uri, bool $preserve_host = false) : PsrRequestInterface
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
     * @see \asbamboo\http\psr\PsrRequestInterface::withRequestTarget()
     *
     * @param string $target
     * @return PsrRequestInterface
     */
    public function withRequestTarget(string $target) : PsrRequestInterface
    {
        $New                    = clone $this;
        $New->request_target    = $target;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrRequestInterface::withMethod()
     *
     * @param string $method
     * @return PsrRequestInterface
     */
    public function withMethod(string $method): PsrRequestInterface
    {
        $New            = clone $this;
        $New->method    = $method;
        return $New;
    }
}