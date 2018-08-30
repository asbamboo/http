<?php
namespace asbamboo\http;

use asbamboo\http\psr\PsrStreamInterface;

/**
 * 实现UploadedFileInterface[根据psr7]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class UploadedFile implements UploadedFileInterface
{
    protected $client_file_name;

    protected $client_media_type;

    protected $tmp_file_name;

    protected $error;

    protected $size;

    public function __construct(string $tmp_file_name, string $client_media_type, int $size, int $error, string $client_file_name)
    {
        $this->tmp_file_name        = $tmp_file_name;
        $this->client_media_type    = $client_media_type;
        $this->size                 = $size;
        $this->error                = $error;
        $this->client_file_name     = $client_file_name;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::getError()
     */
    public function getError() : int
    {
        return $this->error;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::getSize()
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::getClientFilename()
     */
    public function getClientFilename(): ?string
    {
        return $this->client_file_name;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::getStream()
     */
    public function getStream(): PsrStreamInterface
    {
        return new Stream($this->tmp_file_name);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::getClientMediaType()
     */
    public function getClientMediaType(): ?string
    {
        return $this->client_media_type;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrUploadedFileInterface::moveTo()
     */
    public function moveTo(string $target_path) : bool
    {
        return move_uploaded_file($this->tmp_file_name, $target_path);
    }
}
