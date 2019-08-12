<?php


namespace PHPAccounting\Xero\Message\Journals\Requests;


use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Journals\Responses\GetJournalResponse;
use XeroPHP\Models\Accounting\Journal;

class GetJournalRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (JournalID generic interface)
     * @see https://developer.xero.com/documentation/api/journals
     * @param $value
     * @return GetJournalRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/journals
     * @param $value
     * @return GetJournalRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (JournalIDs)
     * @return mixed comma-delimited-string
     */
    public function getAccountingIDs() {
        if ($this->getParameter('accounting_ids')) {
            return implode(', ',$this->getParameter('accounting_ids'));
        }
        return null;
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        if ($this->getParameter('page')) {
            return $this->getParameter('page');
        }

        return 1;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetJournalResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $journals = $xero->loadByGUID(Journal::class, $this->getAccountingIDs());
                }
                else {
                    $journals = $xero->loadByGUIDs(Journal::class, $this->getAccountingIDs());
                }
            } else {
                $journals = $xero->load(Journal::class)->execute();
            }
            $response = $journals;

        } catch (\Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetJournalResponse($this, $data);
    }
}