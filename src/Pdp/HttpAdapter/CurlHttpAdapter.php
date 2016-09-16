<?php

/**
 * PHP Domain Parser: Public Suffix List based URL parsing.
 *
 * @link      http://github.com/jeremykendall/php-domain-parser for the canonical source repository
 *
 * @copyright Copyright (c) 2014 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/php-domain-parser/blob/master/LICENSE MIT License
 */
namespace Pdp\HttpAdapter;

use Pdp\Exception\CurlHttpAdapterException;

/**
 * cURL http adapter.
 *
 * Lifted pretty much completely from William Durand's excellent Geocoder
 * project
 *
 * @link   https://github.com/willdurand/Geocoder Geocoder on GitHub
 *
 * @author William Durand <william.durand1@gmail.com>
 * @author Jeremy Kendall <jeremy@jeremykendall.net>
 */
class CurlHttpAdapter implements HttpAdapterInterface
{
  /**
   * {@inheritdoc}
   */
  public function getContent($url, $timeout = 5)
  {
    // init
    $ch = curl_init();

    if (false === $ch) {
      throw new \Exception('PHP-Domain-Parser: failed to initialize : cURL');
    }

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-Domain-Parser cURL Request');

    $content = curl_exec($ch);

    $errNo = curl_errno($ch);
    if ($errNo) {
      throw new CurlHttpAdapterException("CURL error [{$errNo}]: " . curl_error($ch), $errNo);
    }

    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (
        $responseCode !== 200
        &&
        $responseCode !== 301
        &&
        $responseCode !== 302
    ) {
      throw new CurlHttpAdapterException('Wrong HTTP response code: ' . $responseCode, $responseCode);
    }

    curl_close($ch);

    return $content;
  }
}
