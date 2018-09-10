<?php
namespace asbamboo\http;

use asbamboo\http\psr\UploadedFile AS BaseUploadedFile;

/**
 * 实现UploadedFileInterface[根据psr7]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class UploadedFile extends BaseUploadedFile implements UploadedFileInterface{}
