<?php

namespace Tests;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
use Faker;
class CreateContactTest extends TestCase
{
    public function testCreateContacts()
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
            $gateway->setAccessToken('XKQNI18WR193CCGF90X1MCUVU0WRSM');
            $gateway->setAccessTokenSecret('PLM7QB44QLSHXFCSKVL60PSI9MPKF9');
            $params = [
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email_address' => $faker->email,
                'phones' => [
                    [
                        'type' => 'MOBILE',
                        'country_code' => '61',
                        'phone_number' => '545346432'
                    ]
                ]
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