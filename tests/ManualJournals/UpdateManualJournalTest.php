<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 9/09/2019
 * Time: 4:36 PM
 */

namespace Tests\ManualJournals;


use Tests\BaseTest;

class UpdateManualJournalTest extends BaseTest
{
    public function testCreateManualJournal(){
        $this->setUp();
        try {
            $params = [
                'accounting_id' => '3429204d-33d9-4ff0-a3a4-4305a112f82d',
                'date' => '2019-01-27',
                'narration' => 'Test Manual Journal (Changed Narration)',
                'journal_data' => [
                    [
                        'description' => 'Consulting services as agreed (20% off standard rate)',
                        'gross_amount' => 825,
                        'tax_type' => 'INPUT',
                        'account_code' => '710'
                    ],
                    [
                        'description' => 'Less Consulting services as agreed (20% off standard rate)',
                        'gross_amount' => -825,
                        'tax_type' => 'BASEXCLUDED',
                        'account_code' => '711'
                    ]
                ]
            ];

            $response = $this->gateway->createManualJournal($params)->send();
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