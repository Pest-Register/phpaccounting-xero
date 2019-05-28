<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 28/05/2019
 * Time: 10:31 AM
 */

namespace Tests;


use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
use XeroPHP\Remote\OAuth\Client;

class BaseTest extends TestCase
{
    public $gateway;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
        $config = [
            'type' => 'public',
            'config' => [
                'oauth' => [
                    'callback' => 'localhost',
                    'signature_method' => Client::SIGNATURE_HMAC_SHA1,
                    'consumer_key' => 'LEFVEZ26CAJQXOBLKNZGE5KDAY2HP3',
                    'consumer_secret' => 'LIYZTFSOCIIZUWEYIQBVPHJS8VG39D',
                    'signature_location' => Client::SIGN_LOCATION_QUERY
                ]
            ]
        ];
        $this->gateway->setXeroConfig($config);
        $this->gateway->setAccessToken('0N7KSWOGKPNUPLYPKG1JPMNKPK2LQY');
        $this->gateway->setAccessTokenSecret('GPA8H7JIWSQTZDRHYCNKAMRSWIYOX5');

    }

}