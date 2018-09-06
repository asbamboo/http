<?php
namespace asbamboo\http\psr;

use asbamboo\http\exception\InvalidFileArgumentException;
use asbamboo\http\exception\InvalidServerRequestParseBodyArgumentException;
use asbamboo\http\psr\traits\RequestTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * 实现ServerRequestInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class ServerRequest implements ServerRequestInterface
{
    use RequestTrait;

    /**
     *
     * @var array
     */
    protected $attributes   = [];

    /**
     *
     * @var array
     */
    protected $servers;

    /**
     *
     * @var array
     */
    protected $cookies;

    /**
     *
     * @var array
     */
    protected $querys;

    /**
     *
     * @var array
     */
    protected $uploaded_files;

    /**
     *
     * @var null|array|object
     */
    protected $parsed_body;

    /**
     *
     */
    public function __construct()
    {
        $uri            = $this->getServerParams()['REQUEST_URI'];
        $this->Uri      = new Uri($uri);
        if(isset($this->getServerParams()['REQUEST_METHOD'])){
            $this->method   = $this->getServerParams()['REQUEST_METHOD'];
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getServerParams()
     */
    public function getServerParams(): array
    {
        if(is_null($this->servers)){
            $this->servers  = $_SERVER;
        }
        return $this->servers;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getAttributes()
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getAttribute()
     */
    public function getAttribute(/*string*/ $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getCookieParams()
     */
    public function getCookieParams() : array
    {
        if(is_null($this->cookies)){
            $this->cookies  = $_COOKIE;
        }
        return $this->cookies;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getUploadedFiles()
     */
    public function getUploadedFiles() : array
    {
        if(is_null($this->uploaded_files)){
            $this->uploaded_files   = $_FILES;
            $this->uploaded_files   = $this->convertFiles($this->uploaded_files);
        }
        return $this->uploaded_files;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getQueryParams()
     */
    public function getQueryParams(): array
    {
        if(is_null($this->querys)){
            $this->querys   = $_GET;
        }
        return $this->querys;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::getParsedBody()
     */
    public function getParsedBody()
    {
        if(!is_null($this->parsed_body)){
            return $this->parsed_body;
        }

        if($_POST){
            $this->parsed_body  = $_POST;
            return $this->parsed_body;
        }

        $this->parsed_body  = new Stream('php://input');

        return $this->parsed_body;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withAttribute()
     */
    public function withAttribute(/*string*/ $name, $value) : ServerRequestInterface
    {
        $New                    = clone $this;
        $New->attributes[$name] = $value;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withoutAttribute()
     */
    public function withoutAttribute(/*string*/ $name) : ServerRequestInterface
    {
        $New    = clone $this;
        unset($New->attributes[$name]);
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withCookieParams()
     */
    public function withCookieParams(array $cookies) : ServerRequestInterface
    {
        $New            = clone $this;
        $New->cookies   = $cookies;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withUploadedFiles()
     */
    public function withUploadedFiles(array $uploaded_files): ServerRequestInterface
    {
        $uploaded_files         = $this->convertFiles($uploaded_files);

        $New                    = clone $this;
        $New->uploaded_files    = $uploaded_files;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withQueryParams()
     */
    public function withQueryParams(array $querys): ServerRequestInterface
    {
        $New            = clone $this;
        $New->querys    = $querys;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see ServerRequestInterface::withParsedBody()
     */
    public function withParsedBody(/*null|array|object*/ $data): ServerRequestInterface
    {
        if(!is_null($data) && !is_array($data) && !is_object($data)){
            throw new InvalidServerRequestParseBodyArgumentException('根据psr7的规则ServerRequestInterface::withParsedBody方法的参数只能是[null|array|object]');
        }

        $New                = clone $this;
        $New->parsed_body   = $data;
        return $New;
    }

    /**
     * 将数组$files中的内容转换为实现了UploadedFile类的实例
     *
     * @param array $files 上传的文件,通常为$_FILES(或者可以使用兼容$_FILES的数组)
     * @return array
     */
    private function convertFiles(array $files) : array
    {
        foreach($files AS &$file){

            if($file instanceof UploadedFileInterface){
                continue;
            }

            if(     is_array($file)
                &&  array_key_exists('name', $file) && is_string( $file['name'] )
                &&  array_key_exists('tmp_name', $file) && is_string( $file['tmp_name'] )
                &&  array_key_exists('type', $file) && is_string( $file['type'] )
                &&  array_key_exists('size', $file) && is_int( $file['size'] )
                && array_key_exists('error', $file) && is_int( $file['error'] )
            ){
                $file   = new UploadedFile($file['tmp_name'], $file['type'], $file['size'], $file['error'], $file['name']);
                continue;
            }

            if(is_array( $file )){
                $file   = $this->convertFiles($file);
                continue;
            }

            throw new InvalidFileArgumentException('不是有效的上传文件参数。');
        }

        return $files;
    }
}
