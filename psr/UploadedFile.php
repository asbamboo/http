<?php
namespace asbamboo\http\psr;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

/**
 * 实现UploadedFileInterface[根据psr7]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class UploadedFile implements UploadedFileInterface
{
    /**
     *
     * @var string
     */
    protected $client_file_name;

    /**
     *
     * @var string
     */
    protected $client_media_type;

    /**
     *
     * @var string
     */
    protected $tmp_file_name;

    /**
     *
     * @var int
     */
    protected $error;

    /**
     *
     * @var int
     */
    protected $size;

    /**
     *
     * @param string $tmp_file_name
     * @param string $client_media_type
     * @param int $size
     * @param int $error
     * @param string $client_file_name
     */
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
     * @see UploadedFileInterface::getError()
     */
    public function getError() : int
    {
        return $this->error;
    }

    /**
     *
     * {@inheritDoc}
     * @see UploadedFileInterface::getSize()
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     *
     * {@inheritDoc}
     * @see UploadedFileInterface::getClientFilename()
     */
    public function getClientFilename(): ?string
    {
        return $this->client_file_name;
    }

    /**
     *
     * {@inheritDoc}
     * @see UploadedFileInterface::getStream()
     */
    public function getStream(): StreamInterface
    {
        return new Stream($this->tmp_file_name);
    }

    /**
     *
     * {@inheritDoc}
     * @see UploadedFileInterface::getClientMediaType()
     */
    public function getClientMediaType(): ?string
    {
        return $this->client_media_type;
    }

    /**
     *
     * {@inheritDoc}
     * @see UploadedFileInterface::moveTo()
     */
    public function moveTo(/*string*/ $target_path) : bool
    {
        return move_uploaded_file($this->tmp_file_name, $target_path);
    }
}
