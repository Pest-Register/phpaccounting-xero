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
        $gateway = Omnipay::create('\PHPAccounting\Xero\Gateway');
        var_dump($gateway);
    }
}