<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 9/09/2019
 * Time: 12:01 PM
 */

namespace Tests\Journals;


use Tests\BaseTest;

class CreateManualJournalTest extends BaseTest
{
    public function testCreateManualJournal(){
        $this->setUp();
        try {
            $params = [
                'date' => '2019-01-27',
                'narration' => 'Test Manual Journal',
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