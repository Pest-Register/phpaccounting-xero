<?php

namespace Tests;

use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
use XeroPHP\Remote\Collection;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 9:54 AM
 */

class GetContactTest extends TestCase
{

    public function testHelp()
    {
        try {
            $gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
            $config = [
                'type' => 'public',
                'config' => [
                    'oauth' => [
                        'callback' => 'localhost',
                        'signature_method' => \XeroPHP\Remote\OAuth\Client::SIGNATURE_HMAC_SHA1,
                        'consumer_key' => 'LEFVEZ26CAJQXOBLKNZGE5KDAY2HP3',
                        'consumer_secret' => 'LIYZTFSOCIIZUWEYIQBVPHJS8VG39D',
                        'signature_location' => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_HEADER
                    ]
                ]
            ];
            $gateway->setXeroConfig($config);
            $gateway->setAccessToken('IZ2CWOINVGSDKZFEAHAU2W48YV53L4');
            $gateway->setAccessTokenSecret('IREZGREQBRG9SBXCIEAOLTSYQOVTWK');
            $response = $gateway->getContact()->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContacts());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }


    }
}
