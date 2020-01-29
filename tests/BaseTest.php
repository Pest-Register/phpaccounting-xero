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

        $this->gateway->setClientID(getenv('CLIENT_ID'));
        $this->gateway->setClientSecret(getenv('CLIENT_SECRET'));
        $this->gateway->setTenantID(getenv('TENANT_ID'));
        $this->gateway->setAccessToken(getenv('ACCESS_TOKEN'));
        $this->gateway->setAccessTokenSecret(getenv('ACCESS_TOKEN_SECRET'));
        $this->gateway->setCallbackURL(getenv('CALLBACK_URL'));
    }

}