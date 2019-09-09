<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 9/09/2019
 * Time: 3:41 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class GetManualJournalTest extends BaseTest
{

    public function testGetManualJournals()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => [""],
                'page' => 1
            ];

            $response = $this->gateway->getManualJournal($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getManualJournals());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}