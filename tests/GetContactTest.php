<?php

namespace Tests;

use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
use XeroPHP\Remote\Collection;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 9:54 AM
 */

class GetContactTest extends BaseTest
{

    public function testGetContacts()
    {
        $this->setUp();
        try {
            $params = [
                'accountingIDs' => ["d3b2a39c-d6e8-471c-9ae2-00ebb3c604f3"],
                'page' => 1
            ];

            $response = $this->gateway->getContact($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContacts());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
