<?php

namespace Tests;

use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 9:54 AM
 */

class CreateContactTest extends TestCase
{

    public function testHelp(){
        try{
            $gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
            $config = [
                'type' => 'public',
                'config' => [
                    'oauth' => [
                        'callback' => 'localhost',
                        'signature_method' => \XeroPHP\Remote\OAuth\Client::SIGNATURE_HMAC_SHA1,
                        'consumer_key' => '',
                        'consumer_secret' => '',
                        //If you have issues passing the Authorization header, you can set it to append to the query string
                        'signature_location'    => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY
                        //For certs on disk or a string - allows anything that is valid with openssl_pkey_get_(private|public)
//                        'rsa_private_key' => 'file://certs/private.pem',
//                        'rsa_public_key' => 'file://certs/public.pem',
                    ]
                ]
            ];
            $gateway->setXeroConfig($config);
            $gateway->setAccessToken('');
            $gateway->setAccessTokenSecret('');
            $response = $gateway->getContact()->send();
            if($response->isSuccessful()){
                echo '<pre>';
                var_dump($response->getData());
                echo '</pre>';
            }
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }


    }
}