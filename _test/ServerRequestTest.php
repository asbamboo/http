<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\ServerRequest;

/**
 * test ServerRequest
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class ServerRequestTest extends TestCase
{
    public function setUp(){
        $_SERVER['REQUEST_URI'] = "http://username:password@hostname:80/path?arg1=value1&arg2=value2#anchor";
    }

    public function testGetServerParams()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals($_SERVER, $ServerRequest->getServerParams());
    }

    public function testGetAttributes()
    {
        $ServerRequest          = new ServerRequest();
        $New                    = $ServerRequest->withAttribute('_format', 'html');
        $this->assertEquals([], $ServerRequest->getAttributes());
        $this->assertEquals(['_format' => 'html'], $New->getAttributes());
        return $New;
    }

    public function testGetAttribute()
    {
        $ServerRequest          = new ServerRequest();
        $New                    = $ServerRequest->withAttribute('_format', 'html');
        $this->assertNull($ServerRequest->getAttribute('_format'));
        $this->assertEquals('html', $ServerRequest->getAttribute('_format', 'html'));
        $this->assertEquals('html', $New->getAttribute('_format'));
    }

    public function testGetCookieParams()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals($_COOKIE, $ServerRequest->getCookieParams());
        $_COOKIE['test']        = 'test';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals($_COOKIE, $ServerRequest->getCookieParams());
        return $ServerRequest;
    }

    public function testGetCookieParam()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals($_COOKIE, $ServerRequest->getCookieParams());
        $_COOKIE['test']        = 'test';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('test', $ServerRequest->getCookieParam('test'));
        return $ServerRequest;
    }

    public function testGetUploadedFiles()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals([], $ServerRequest->getUploadedFiles());

        $file['tmp_name']   = tempnam('/tmp', 'dobuild');
        $file['type']       = 'text/html';
        $file['size']       = 0;
        $file['error']      = UPLOAD_ERR_OK;
        $file['name']       = 'test';
        $_FILES[0]['a']     = $file;
        $_FILES['b']        = $file;
        $_FILES['c']        = $file;
        $_FILES[0][0]['d']  = $file;
        $ServerRequest      = new ServerRequest();
        $files              = $ServerRequest->getUploadedFiles();
        $this->assertEquals('test', $files[0]['a']->getClientFilename());
        $this->assertEquals('test', $files['b']->getClientFilename());
        $this->assertEquals('test', $files['c']->getClientFilename());
        $this->assertEquals('test', $files[0][0]['d']->getClientFilename());
        return $ServerRequest;
    }

    public function testGetQueryParams()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals([], $ServerRequest->getQueryParams());
        $_GET['arg1']           = 'val1';
        $_GET['arg2']           = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getQueryParams()['arg1']);
        $this->assertEquals('val2', $ServerRequest->getQueryParams()['arg2']);
        return $ServerRequest;
    }

    public function testGetQueryParam()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('default', $ServerRequest->getQueryParam('default','default'));
        $_GET['arg1']           = 'val1';
        $_GET['arg2']           = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getQueryParam('arg1'));
        $this->assertEquals('val2', $ServerRequest->getQueryParam('arg2'));
        return $ServerRequest;
    }

    public function testGetRequestParams()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals([], $ServerRequest->getRequestParams());
        $_REQUEST['arg1']          = 'val1';
        $_REQUEST['arg2']          = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getRequestParams()['arg1']);
        $this->assertEquals('val2', $ServerRequest->getRequestParams()['arg2']);
        return $ServerRequest;
    }

    public function testGetRequestParam()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('default', $ServerRequest->getRequestParam('default','default'));
        $_REQUEST['arg1']          = 'val1';
        $_REQUEST['arg2']          = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getRequestParam('arg1'));
        $this->assertEquals('val2', $ServerRequest->getRequestParam('arg2'));
        return $ServerRequest;
    }

    public function testGetPostParams()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals([], $ServerRequest->getPostParams());
        $_POST['arg1']          = 'val1';
        $_POST['arg2']          = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getPostParams()['arg1']);
        $this->assertEquals('val2', $ServerRequest->getPostParams()['arg2']);
        return $ServerRequest;
    }

    public function testGetPostParam()
    {
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('default', $ServerRequest->getPostParam('default','default'));
        $_POST['arg1']          = 'val1';
        $_POST['arg2']          = 'val2';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals('val1', $ServerRequest->getPostParam('arg1'));
        $this->assertEquals('val2', $ServerRequest->getPostParam('arg2'));
        return $ServerRequest;
    }

    public function testGetParsedBody()
    {
        $_POST['test']          = '2121';
        $ServerRequest          = new ServerRequest();
        $this->assertEquals($_POST, $ServerRequest->getParsedBody());

        return $ServerRequest;
    }

    /**
     * @depends testGetAttributes
     */
    public function testWithoutAttribute(ServerRequest $ServerRequest)
    {
        $New    = $ServerRequest->withoutAttribute('_format');
        $this->assertEquals('html', $ServerRequest->getAttribute('_format'));
        $this->assertEquals(null, $New->getAttribute('_format'));
    }

    /**
     * @depends testGetCookieParams
     */
    public function testWithCookieParams(ServerRequest $ServerRequest)
    {
        $New    = $ServerRequest->withCookieParams(['test'=>'withCookieParams']);
        $this->assertEquals(['test'=>'test'], $ServerRequest->getCookieParams());
        $this->assertEquals(['test'=>'withCookieParams'], $New->getCookieParams());
    }

    /**
     *
     * @depends testGetUploadedFiles
     */
    public function testWithUploadedFiles(ServerRequest $ServerRequest)
    {
        $files                  = $ServerRequest->getUploadedFiles();
        $file['tmp_name']       = tempnam('/tmp', 'dobuild');
        $file['type']           = 'text/html';
        $file['size']           = 0;
        $file['error']          = UPLOAD_ERR_OK;
        $file['name']           = 'test';
        $files['file']          = $file;
        $New                    = $ServerRequest->withUploadedFiles($files);

        $this->assertArrayNotHasKey('file', $ServerRequest->getUploadedFiles());

        $files                      = $New->getUploadedFiles();
        $this->assertEquals('test', $files[0]['a']->getClientFilename());
        $this->assertEquals('test', $files['b']->getClientFilename());
        $this->assertEquals('test', $files['c']->getClientFilename());
        $this->assertEquals('test', $files[0][0]['d']->getClientFilename());
        $this->assertEquals('test', $files['file']->getClientFilename());
    }

    /**
     * @depends testGetQueryParams
     */
    public function testWithQueryParams(ServerRequest $ServerRequest)
    {
        $New    = $ServerRequest->withQueryParams(['q1'=>'v1', 'q2'=>'v2']);
        $this->assertEquals('val1', $ServerRequest->getQueryParams()['arg1']);
        $this->assertEquals('val2', $ServerRequest->getQueryParams()['arg2']);
        $this->assertEquals('v1', $New->getQueryParams()['q1']);
        $this->assertEquals('v2', $New->getQueryParams()['q2']);
    }

    /**
     * @depends testGetParsedBody
     */
    public function testWithParsedBody(ServerRequest $ServerRequest)
    {
        $New    = $ServerRequest->withParsedBody(['body'=>'withParsedBody']);
        $this->assertEquals($_POST, $ServerRequest->getParsedBody());
        $this->assertEquals(['body'=>'withParsedBody'], $New->getParsedBody());
    }

}
