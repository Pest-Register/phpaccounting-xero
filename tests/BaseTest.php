<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 28/05/2019
 * Time: 10:31 AM
 */

namespace Tests;


use Dotenv\Dotenv;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
use XeroPHP\Remote\OAuth\Client;

class BaseTest extends TestCase
{
    public $gateway;

    public function setUp()
    {
        parent::setUp();
        $dotenv = Dotenv::create(__DIR__ . '/..');
        $dotenv->load();
        $this->gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
        $config = [
            'type' => 'public',
            'config' => [
                'oauth' => [
                    'callback' => getenv('CALLBACK_URL'),
                    'signature_method' => getenv('SIGNATURE_METHOD'),
                    'consumer_key' => getenv('CONSUMER_KEY'),
                    'consumer_secret' => getenv('CONSUMER_SECRET'),
                    'signature_location' => getenv('SIGNATURE_LOCATION')
                ]
            ]
        ];
        $this->gateway->setXeroConfig($config);
        $this->gateway->setAccessToken(getenv('ACCESS_TOKEN'));
        $this->gateway->setAccessTokenSecret(getenv('ACCESS_TOKEN_SECRET'));
    }

}