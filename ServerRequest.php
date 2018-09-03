<?php
namespace asbamboo\http;

use asbamboo\http\traits\RequestTrait;
use asbamboo\http\exception\InvalidFileArgumentException;
use asbamboo\http\exception\InvalidServerRequestParseBodyArgumentException;
use asbamboo\http\psr\PsrServerRequestInterface;

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
    protected $requests;

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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getServerParams()
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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getAttributes()
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getAttribute()
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getCookieParams()
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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getUploadedFiles()
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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getQueryParams()
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
     * @see \asbamboo\http\ServerRequestInterface::getRequestParams()
     */
    public function getRequestParams(): array
    {
        if(is_null($this->requests)){
            $this->requests   = array_merge($_GET, $_POST);
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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::getParsedBody()
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
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withAttribute()
     */
    public function withAttribute(string $name, $value) : PsrServerRequestInterface
    {
        $New                    = clone $this;
        $New->attributes[$name] = $value;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withoutAttribute()
     */
    public function withoutAttribute(string $name) : PsrServerRequestInterface
    {
        $New    = clone $this;
        unset($New->attributes[$name]);
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withCookieParams()
     */
    public function withCookieParams(array $cookies) : PsrServerRequestInterface
    {
        $New            = clone $this;
        $New->cookies   = $cookies;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withUploadedFiles()
     */
    public function withUploadedFiles(array $uploaded_files): PsrServerRequestInterface
    {
        $uploaded_files         = $this->convertFiles($uploaded_files);

        $New                    = clone $this;
        $New->uploaded_files    = $uploaded_files;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withQueryParams()
     */
    public function withQueryParams(array $querys): PsrServerRequestInterface
    {
        $New            = clone $this;
        $New->querys    = $querys;
        return $New;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrServerRequestInterface::withParsedBody()
     */
    public function withParsedBody(/*null|array|object*/ $data): PsrServerRequestInterface
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
