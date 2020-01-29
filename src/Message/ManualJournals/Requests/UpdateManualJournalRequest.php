<?php
namespace PHPAccounting\Xero\Message\ManualJournals\Requests;
use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ManualJournals\Responses\UpdateManualJournalResponse;
use XeroPHP\Models\Accounting\ManualJournal;
use XeroPHP\Models\Accounting\ManualJournal\JournalLine;

class UpdateManualJournalRequest extends AbstractRequest
{
    /**
     * Get Accounting ID Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getAccountingID(){
        return $this->getParameter('accounting_id');
    }

    /**
     * Set Accounting ID Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setAccountingID($value){
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getNarration(){
        return $this->getParameter('narration');
    }

    /**
     * Set Narration Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setNarration($value){
        return $this->setParameter('narration', $value);
    }

    /**
     * Get Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getJournalData(){
        return $this->getParameter('journal_data');
    }

    /**
     * Set Journal Data Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setJournalData($value){
        return $this->setParameter('journal_data', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Status
     * @return UpdateManualJournalRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param string $value Date
     * @return UpdateManualJournalRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    private function addJournalLinesToJournal(ManualJournal $journal, $data) {
        foreach($data as $lineData) {
            $lineItem = new JournalLine();
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('gross_amount', $lineData, $lineItem, 'setLineAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type', $lineData, $lineItem, 'setTaxType');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('description', $lineData, $lineItem, 'setDescription');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('account_code', $lineData, $lineItem, 'setAccountCode');
            $journal->addJournalLine($lineItem);
        }
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('narration', 'journal_data', 'accounting_id');

        $this->issetParam('ManualJournalID', 'accounting_id');
        $this->issetParam('Narration', 'narration');
        $this->issetParam('Date', 'date');
        $this->issetParam('JournalLines', 'journal_data');

        return $this->data;
    }
    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return UpdateManualJournalResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            $journal = new ManualJournal($xero);
            foreach ($data as $key => $value){
                if ($key === 'JournalLines') {
                    $this->addJournalLinesToJournal($journal, $value);
                } elseif ($key === 'Date' || $key === 'DueDate') {
                    $methodName = 'set'. $key;
                    $date = \DateTime::createFromFormat('Y-m-d H:m:s', $value);
                    $journal->$methodName($date);
                } else {
                    $methodName = 'set'. $key;
                    $journal->$methodName($value);
                }

            }
            $response = $journal->save();
        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }


    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateManualJournalResponse($this, $data);
    }
}