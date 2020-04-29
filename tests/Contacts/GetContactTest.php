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
                'accounting_ids' => [""],
                'page' => 2
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
