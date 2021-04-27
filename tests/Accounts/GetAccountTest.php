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

class GetAccountTest extends BaseTest
{

    public function testGetAccounts()
    {
        $this->setUp();
        try {
            $params = [
//                'search_params' => [
//                    'Name' => 'Sales',
//                ],
//                'search_filters' => [
//                    'Type' => [
//                        'BANK',
//                        'CURRLIAB',
//                        'EQUITY',
//                        'REVENUE'
//                    ]
//                ],
//                'match_all_filters' => false,
                'accounting_ids' => ["34464278-2cad-4382-9225-74771a041abb"],
                'page' => 1
            ];

            $response = $this->gateway->getAccount($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getAccounts());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
