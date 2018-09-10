<?php
namespace asbamboo\http\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\http\RedirectResponse;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月1日
 */
class RedirectResponseTest extends TestCase
{
    public function testMain()
    {
        $target_uri         = 'test_target_uri';
        $RedirectResponse   = new RedirectResponse($target_uri);
        $location           = $RedirectResponse->getHeader('location');

        $this->assertEquals([$target_uri], $location);
    }
}
