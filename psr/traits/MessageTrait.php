<?php
namespace asbamboo\http\psr\traits;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
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
     * @var StreamInterface
     */
    protected $Body;

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::getProtocolVersion()
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
     * @see MessageInterface::hasHeader()
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader(/*string*/ $name): bool
    {
        $name   = strtolower($name);
        return array_key_exists($name, $this->headers);
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::getHeader()
     *
     * @param string $name
     * @return array
     */
    public function getHeader(/*string*/ $name) : array
    {
        if(isset($this->headers[$name])){
            return (array) $this->headers[$name];
        };
        $name   = strtolower($name);
        return (array) ($this->headers[$name] ?? []);
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::getHeaders()
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
     * @see MessageInterface::getHeaderLine()
     *
     * @param string $name
     * @return string
     */
    public function getHeaderLine(/*string*/ $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::getBody()
     *
     * @return StreamInterface
     */
    public function getBody() : StreamInterface
    {
        return $this->Body;
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::withProtocolVersion()
     *
     * @param string $version
     * @return MessageInterface
     */
    public function withProtocolVersion(/*string*/ $version) : MessageInterface
    {
        $New            = clone $this;
        $New->version   = $version;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::withHeader()
     *
     * @param string $name
     * @param array|string $value
     * @return MessageInterface
     */
    public function withHeader(/*string*/ $name, /*array|string*/$value) : MessageInterface
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
     * @see MessageInterface::withAddedHeader()
     *
     * @param string $name
     * @param array|string $value
     * @return MessageInterface
     */
    public function withAddedHeader(/*string*/ $name, /*array|string*/$value) : MessageInterface
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
     * @see MessageInterface::withoutHeader()
     *
     * @param string $name
     * @return MessageInterface
     */
    public function withoutHeader(/*string*/ $name) : MessageInterface
    {
        $name       = strtolower($name);

        $New    = clone $this;
        unset($New->headers[$name]);
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see MessageInterface::withBody()
     *
     * @param StreamInterface $Body
     * @return MessageInterface
     */
    public function withBody(StreamInterface $Body) : MessageInterface
    {
        $New        = clone $this;
        $New->Body  = $Body;
        return $New;
    }
}
