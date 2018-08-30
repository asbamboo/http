<?php
namespace asbamboo\http;

use asbamboo\http\psr\PsrUploadedFileInterface;

/**
 * 继承遵守psr规则的UploadedFileInterface，并在此基础上扩展
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月19日
 */
interface UploadedFileInterface extends PsrUploadedFileInterface
{
}
