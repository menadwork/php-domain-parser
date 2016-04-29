<?php

namespace Pdp\HttpAdapter;

/**
 * Class CurlHttpAdapterTest
 *
 * @package Pdp\HttpAdapter
 */
class CurlHttpAdapterTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var HttpAdapterInterface
   */
  protected $adapter;

  protected function setUp()
  {
    if (!function_exists('curl_init')) {
      self::markTestSkipped('cURL has to be enabled.');
    }

    $this->adapter = new CurlHttpAdapter();
  }

  protected function tearDown()
  {
    $this->adapter = null;
  }

  public function testGetContent()
  {
    $content = $this->adapter->getContent('http://www.google.com');
    self::assertNotNull($content);
    self::assertContains('google', $content);
  }
}
