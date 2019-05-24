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
                        'consumer_key' => 'LEFVEZ26CAJQXOBLKNZGE5KDAY2HP3',
                        'consumer_secret' => 'LIYZTFSOCIIZUWEYIQBVPHJS8VG39D',
                        'signature_location' => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY
                    ]
                ]
            ];
            $gateway->setXeroConfig($config);
            $gateway->setAccessToken('3XO4ECQIGEGLACV4UPPAXSTJ0UQ1AO');
            $gateway->setAccessTokenSecret('7JTQX9IDKUQIEV3ODSPPQLEVHIG1Q0');
            $params = [
                'accountingIDs' => ["50a40408-2e0b-4337-bbf8-de749be9fc9d"],
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
