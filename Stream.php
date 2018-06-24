<?php
namespace asbamboo\http;

use asbamboo\http\exception\NotStreamException;

/**
 * 实现StreamInterface[根据psr7]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class Stream implements StreamInterface
{
    /**
     * stream类型的resource
     *
     * @var resource
     */
    protected $resource;

    /**
     *
     * @param resource $resource stream类型的resource
     * @throws NotStreamException
     */
    public function __construct(/*stream*/ $resource, string $mode = 'r')
    {
        if(is_string($resource)){
            $resource   = fopen($resource, $mode);
        }

        if(!is_resource($resource) || get_resource_type($resource) != 'stream'){
            throw new NotStreamException('$resource 不是stream类型');
        }
        $this->resource = $resource;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::getMetadata()
     */
    public function getMetadata(string $key = null)
    {
        $meta_data  = stream_get_meta_data($this->resource);
        return !is_null($key) && array_key_exists($key, $meta_data) ? $meta_data[$key] : $meta_data;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::read()
     */
    public function read(int $length): string
    {
        return stream_get_contents($this->resource, $length);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::getContents()
     */
    public function getContents() : string
    {
       return stream_get_contents($this->resource);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::write()
     */
    public function write(string $string): int
    {
        return fwrite($this->resource, $string);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::rewind()
     */
    public function rewind() : bool
    {
        return rewind($this->resource);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::seek()
     */
    public function seek(int $offset, int $whence = SEEK_SET) : int
    {
        return fseek($this->resource, $offset, $whence);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::tell()
     */
    public function tell(): int
    {
        return ftell($this->resource);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::eof()
     */
    public function eof() : bool
    {
        return feof($this->resource);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::getSize()
     */
    public function getSize() : ?int
    {
        /*
         * @var size 返回值
         */
        $size   = 0;

        /*
         * 获取size
         */
        if(stream_is_local($this->resource)){   //本地流
            $size   = fstat($this->resource)['size'];
        }else if(!$this->eof()){ //远端文件，尚未全部读取
            $tell   = $this->tell();
            $this->getContents();
            $size   = $this->tell() + 1;
            if($this->isSeekable()){
                $this->seek($tell);
            }
        }else{  //远端文件，已经全部读取
            $size   = $this->tell() + 1;
        }

        /*
         * 返回
         */
        return $size;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::isReadable()
     */
    public function isReadable() : bool
    {
        $mode           = $this->getMetadata('mode');
        $test_mode_data = str_split($mode);
        return in_array('r', $test_mode_data) || in_array('+', $test_mode_data);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::isWritable()
     */
    public function isWritable(): bool
    {
        $mode           = $this->getMetadata('mode');
        $test_mode_data = str_split($mode);
        return in_array('w', $test_mode_data) || in_array('a', $test_mode_data) || in_array('x', $test_mode_data) || in_array('c', $test_mode_data);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::isSeekable()
     */
    public function isSeekable(): bool
    {
        return $this->getMetadata('seekable');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::close()
     */
    public function close() : void
    {
        fclose($this->resource);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::detach()
     */
    public function detach() /*: ?resource*/
    {
        $this->close();
        $this->resource = null;
        return $this->resource;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrStreamInterface::__toString()
     */
    public function __toString() : string
    {
        $contents   = '';

        $tell   = $this->tell();

        if($this->isReadable()){
            $this->rewind();
            $contents   = $this->getContents();
        }

        if($this->isSeekable()){
            $this->seek($tell);
        }

        return $contents;
    }
}