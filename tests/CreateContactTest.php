<?php

namespace Tests;

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
        $gateway = PHPAccounting::create('MYOB');
        dd($gateway);
    }
}