<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:14 PM.
 */

namespace MoceanTest\Client\Credentials;

use MoceanTest\AbstractTesting;

class BasicCredentialsTest extends AbstractTesting
{
    public function testDirectAccessByArray()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);

        $this->assertTrue(isset($credentials['mocean-api-key']));
        $this->assertEquals($this->apiKey, $credentials['mocean-api-key']);
        $this->assertEquals($this->apiSecret, $credentials['mocean-api-secret']);
    }

    public function testDirectAccessByProperties()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);

        $this->assertEquals($this->apiKey, $credentials->{'mocean-api-key'});
        $this->assertEquals($this->apiSecret, $credentials->{'mocean-api-secret'});
    }

    public function testDataAsArray()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);

        $crendentialsArray = $credentials->asArray();
        $this->assertEquals($this->apiKey, $crendentialsArray['mocean-api-key']);
        $this->assertEquals($this->apiSecret, $crendentialsArray['mocean-api-secret']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExplicitSetData()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);
        $credentials['mocean-api-key'] = 'test_api_key';

        return $credentials;
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExplicitUnsetData()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);
        unset($credentials['mocean-api-key']);
    }
}
