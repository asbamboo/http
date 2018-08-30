<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\Stream;

/**
 * Test Stream
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月24日
 */
class StreamTest extends TestCase
{
    public function testGetMetadata()
    {
        $Stream     = new Stream('php://temp', 'w+b');

        $metadata   = $Stream->getMetadata();
        $this->assertTrue(is_array($metadata));
        $this->assertEquals('PHP', $Stream->getMetadata('wrapper_type'));
        $this->assertEquals('TEMP', $Stream->getMetadata('stream_type'));
        $this->assertEquals('w+b', $Stream->getMetadata('mode'));
        $this->assertEquals(true, $Stream->getMetadata('seekable'));
        $this->assertEquals('php://temp', $Stream->getMetadata('uri'));
    }

    public function testWrite()
    {
        $Stream     = new Stream('php://temp', 'w+b');
        $Stream->write('testdata');
        $this->assertEquals(8, $Stream->getSize());
        return $Stream;
    }

    /**
     * @depends testWrite
     */
    public function testRead($Stream)
    {
        $this->assertEquals('', $Stream->read(4));
        $Stream->rewind();
        $this->assertEquals('test', $Stream->read(4));
        $this->assertEquals('data', $Stream->read(4));
        $Stream->rewind();
        $this->assertEquals('testdata', $Stream->read(20));
    }

    /**
     *
     * @depends testWrite
     */
    public function testGetContents($Stream)
    {
        $this->assertEquals('', $Stream->getContents());
        $Stream->rewind();
        $this->assertEquals('testdata', $Stream->getContents());
    }

    /**
     * @depends testWrite
     */
    public function testSeek($Stream)
    {
        $this->assertEquals(0, $Stream->seek(5));
        $this->assertEquals(5, $Stream->tell());
    }

    /**
     * @depends testWrite
     */
    public function testEof($Stream)
    {
        $this->assertFalse($Stream->eof());
        $Stream->getContents();
        $this->assertTrue($Stream->eof());
    }

    public function testGetSize()
    {
        $Stream     = new Stream('http://php.net', 'r');
        $this->assertGreaterThan(0, $Stream->getSize());
    }

    public function testIsReadable()
    {
        $Stream     = new Stream('php://temp', 'r');
        $this->assertTrue($Stream->isReadable());
        $Stream     = new Stream(tempnam('/tmp', 'tmp'), 'w');
        $this->assertFalse($Stream->isReadable());
    }

    public function testIsWritable()
    {
        $Stream     = new Stream('php://temp', 'r');
        $this->assertFalse($Stream->isWritable());
        $Stream     = new Stream(tempnam('/tmp', 'tmp'), 'w');
        $this->assertTrue($Stream->isWritable());
    }

    public function testClose()
    {
        $Stream  = new class('php://temp', 'r') extends Stream{
            public function getIsResource(){
                return is_resource($this->resource);
            }
        };
        $Stream->close();
        $this->assertFalse($Stream->getIsResource());
    }

    public function testDetach()
    {
        $Stream  = new class('php://temp', 'r') extends Stream{
            public function getResource(){
                return $this->resource;
            }
        };
        $Stream->detach();
        $this->assertNull($Stream->getResource());
    }

    /**
     * @depends testWrite
     */
    public function testToString($Stream)
    {
        $tell   = $Stream->tell();
        $this->assertEquals('testdata', $Stream);
        $this->assertEquals($tell, $Stream->tell());
        $Stream->read(3);
        $this->assertEquals('testdata', $Stream);
        $this->assertEquals($tell, $Stream->tell());

        $Stream     = new Stream(tempnam('/tmp', 'tmp'), 'w');
        $Stream->write('123456');
        $this->assertEquals('', $Stream);
    }
}