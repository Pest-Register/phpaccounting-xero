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

    public function testGetContacts()
    {
        try {
            $gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
            $config = [
                'type' => 'public',
                'config' => [
                    'oauth' => [
                        'callback' => 'localhost',
                        'signature_method' => \XeroPHP\Remote\OAuth\Client::SIGNATURE_HMAC_SHA1,
                        'consumer_key' => '',
                        'consumer_secret' => '',
                        'signature_location' => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY
                    ]
                ]
            ];
            $gateway->setXeroConfig($config);
            $gateway->setAccessToken('');
            $gateway->setAccessTokenSecret('');
            $params = [
                'accountingIDs' => [""],
                'page' => 1
            ];

            $response = $gateway->getContact($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContacts());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
