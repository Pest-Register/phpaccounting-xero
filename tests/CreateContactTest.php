<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 24/05/2019
 * Time: 3:31 PM
 */

namespace Tests;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;

class CreateContactTest extends TestCase
{
    public function testCreateContacts()
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
                'name' => 'Test Account',
                'first_name' => 'Test',
                'last_name' => 'Account',
                'email_address' => 'test@pestregister.com',
                'is_individual' => true
            ];

            $response = $gateway->createContact($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContacts());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}