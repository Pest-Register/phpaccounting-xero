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
            $response = $gateway->createContact([
                'name' => 'dolan'
            ])->send()->isSuccessful();
            var_dump($response);
        }catch (\Exception $exception){
            var_dump($exception);
        }


    }
}