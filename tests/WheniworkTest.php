<?php

require_once __DIR__ . '/../src/Wheniwork.php';

/**
* Test for the client library.
*
* Contributors:
* Daniel Olfelt <daniel@thisclicks.com>
*
* @author Daniel Olfelt <daniel@thisclicks.com>
* @version 0.1
*/

class WheniworkTest extends PHPUnit_Framework_TestCase {

  public function testSetEndpoint()
  {
    $new_endpoint = 'http://example.com/2';
    $wiw = new Wheniwork();
    $wiw->setEndpoint($new_endpoint);

    $this->assertEquals($new_endpoint, $wiw->getEndpoint());
  }

  public function testSetHeaders()
  {
    $headers = ['Sample-Header' => 'Test string'];
    $wiw = new Wheniwork(null, ['headers' => $headers]);

    $this->assertArrayHasKey('Sample-Header', $wiw->getHeaders());

    $wiw->setHeaders(['Another-Sample' => 'Test two']);
    $this->assertEquals(2, count($wiw->getHeaders()));
  }

  public function testSetToken()
  {
    $token = 'i-love-my-boss';
    $wiw = new Wheniwork($token);

    $this->assertEquals($token, $wiw->getToken());

    $wiw->setToken('wrong-value');

    $this->assertNotEquals($token, $wiw->getToken());
  }

}
