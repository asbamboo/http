<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\UploadedFile;
use asbamboo\http\Stream;

/**
 * test UploadFile
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class UploadedFileTest extends TestCase
{
    public function testMain()
    {

        $tmp_file               = tempnam('/tmp', 'dobuild');
        file_put_contents($tmp_file, 'testdata');
        $UploadFile = new UploadedFile($tmp_file, 'text/html', 0, UPLOAD_ERR_OK, 'testfile');
        $this->assertEquals('testfile', $UploadFile->getClientFilename());
        $this->assertEquals('text/html', $UploadFile->getClientMediaType());
        $this->assertEquals(0, $UploadFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $UploadFile->getError());
        $this->assertInstanceOf(Stream::class, $UploadFile->getStream());
        $this->assertTrue($UploadFile->moveTo('/tmp/asbambootestuploadfile'));
        $this->assertEquals('testdata', file_get_contents('/tmp/asbambootestuploadfile'));
        unlink('/tmp/asbambootestuploadfile');
    }
}

/**
 * php move_uploaded_file安全机制需要检查文件是否是正常途径上传。
 * 这个方法在测试时临时覆盖move_uploaded_file方法，摆脱检查，直接江文件移动到目标位置
 */
namespace asbamboo\http;
function move_uploaded_file($filename, $destination){
    return rename($filename, $destination);
}