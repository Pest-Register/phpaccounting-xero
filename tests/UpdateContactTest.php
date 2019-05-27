<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;
class UpdateContactTest
{
    public function testUpdateContact()
    {
        $faker = Faker\Factory::create();
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
            $gateway->setAccessToken('JQZCT8GJ2HC1JZJVD1BBHE1MS9CLYU');
            $gateway->setAccessTokenSecret('PTO5J7KJH4NBVQE5KMZZQIHTEHHQEZ');
            $params = [
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email_address' => $faker->email,
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