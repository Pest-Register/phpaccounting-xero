<?php

namespace Tests;

use Omnipay\Omnipay;
use PHPAccounting\PHPAccounting;
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
        $gateway = Omnipay::create('myob');
        dd($gateway);
    }
}