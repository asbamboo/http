<?php
namespace asbamboo\http\traits;

use asbamboo\http\psr\PsrMessageInterface;
use asbamboo\http\psr\PsrStreamInterface;
use asbamboo\http\StreamInterface;
use asbamboo\http\exception\InvalidStreamException;

trait MessageTrait
{
    /**
     *
     * @var string
     */
    protected $version  = '1.1';

    /**
     *
     * @var array
     */
    protected $headers  = []/*[]*/;

    /**
     *
     * @var PsrStreamInterface
     */
    protected $Body;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::getProtocolVersion()
     *
     * @return string
     */
    public function getProtocolVersion() : string
    {
        return $this->version;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::hasHeader()
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        $name   = strtolower($name);
        return array_key_exists($name, $this->headers);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::getHeader()
     *
     * @param string $name
     * @return array
     */
    public function getHeader(string $name) : array
    {
        $name   = strtolower($name);
        return $this->headers[$name] ?? [];
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::getHeaders()
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::getHeaderLine()
     *
     * @param string $name
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\MessageInterface::getBody()
     *
     * @return PsrStreamInterface
     */
    public function getBody() : PsrStreamInterface
    {
        return $this->Body;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::withProtocolVersion()
     *
     * @param string $version
     * @return PsrMessageInterface
     */
    public function withProtocolVersion(string $version) : PsrMessageInterface
    {
        $New            = clone $this;
        $New->version   = $version;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::withHeader()
     *
     * @param string $name
     * @param array|string $value
     * @return PsrMessageInterface
     */
    public function withHeader(string $name, /*array|string*/$value) : PsrMessageInterface
    {
        $name       = strtolower($name);
        $value      = (array) $value;

        $New                    = clone $this;
        $New->headers[$name]    = $value;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::withAddedHeader()
     *
     * @param string $name
     * @param array|string $value
     * @return PsrMessageInterface
     */
    public function withAddedHeader(string $name, /*array|string*/$value) : PsrMessageInterface
    {
        $name       = strtolower($name);
        $value      = (array) $value;

        $this->headers[$name]   = $this->headers[$name] ?? [];
        $headers                = array_merge($this->headers[$name], $value);

        $New                    = clone $this;
        $New->headers[$name]    = $headers;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::withoutHeader()
     *
     * @param string $name
     * @return PsrMessageInterface
     */
    public function withoutHeader(string $name) : PsrMessageInterface
    {
        $name       = strtolower($name);

        $New    = clone $this;
        unset($New->headers[$name]);
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrMessageInterface::withBody()
     *
     * @param PsrStreamInterface $Body
     * @return PsrMessageInterface
     */
    public function withBody(PsrStreamInterface $Body) : PsrMessageInterface
    {
        if(! $Body instanceof StreamInterface){
            throw new InvalidStreamException('无效的Body参数.');
        }

        $New        = clone $this;
        $New->Body  = $Body;
        return $New;
    }
}
