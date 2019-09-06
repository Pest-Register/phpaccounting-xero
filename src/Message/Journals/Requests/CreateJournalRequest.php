<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 6/09/2019
 * Time: 3:12 PM
 */

namespace PHPAccounting\Xero\Message\Journals\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use XeroPHP\Models\Accounting\ManualJournal;

class CreateJournalRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('narration', 'journal_data');

        $this->issetParam('Narration', 'narration');
        $this->issetParam('Date', 'date');
        $this->issetParam('Status', 'status');
        $this->data['JournalLines'] = ($this->getJournalLines() != null ? $this->getJournalLinesData($this->getJournalLines()) : null);

        return $this->data;
    }

    private function addJournalLinesToJournal() {

    }
    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return CreateInventoryItemResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $item = new ManualJournal($xero);
            foreach ($data as $key => $value){
                if ($key === 'JournalLines') {
                    $this->addJournalLinesToJournal($item, $value);
                } else {
                    $methodName = 'set'. $key;
                    $item->$methodName($value);
                }

            }
            $response = $item->save();
        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }
}