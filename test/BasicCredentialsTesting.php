<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:14 PM.
 */

namespace MoceanTest;

class BasicCredentialsTesting extends AbstractTesting
{
    public function testDirectAccessByArray()
    {
        $credentials = new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret);

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
}
