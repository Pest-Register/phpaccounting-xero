<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 9/09/2019
 * Time: 4:45 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class DeleteManualJournalTest extends BaseTest
{
    public function testDeleteManualJournal(){
        $this->setUp();
        try {
            $params = [
                'accounting_id' => '36a6a389-d456-4a85-b027-6ac90a67d961',
                'status' => 'VOIDED'
            ];

            $response = $this->gateway->deleteManualJournal($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getManualJournals());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}