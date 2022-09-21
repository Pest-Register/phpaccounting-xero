<?php


namespace PHPAccounting\Xero\Message\ManualJournals\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\ManualJournals\Responses\GetManualJournalResponse;
use XeroPHP\Models\Accounting\ManualJournal;
use XeroPHP\Remote\Exception;

class GetManualJournalRequest extends AbstractXeroRequest
{
    public string $model = 'ManualJournal';

    /**
     * Set AccountingID from Parameter Bag (JournalID generic interface)
     * @see https://developer.xero.com/documentation/api/journals
     * @param $value
     * @return GetManualJournalRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (JournalID generic interface)
     * @see https://developer.xero.com/documentation/api/journals
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }
    /**
     * Set AccountingID from Parameter Bag (JournalID generic interface)
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param $value
     * @return GetManualJournalRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/manual-journals
     * @param $value
     * @return GetManualJournalRequest
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
     * @return \Omnipay\Common\Message\ResponseInterface|GetManualJournalResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingID()) {
                $journals = $xero->loadByGUID(ManualJournal::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $journals = $xero->loadByGUID(ManualJournal::class, $this->getAccountingIDs());
                }
                else {
                    $journals = $xero->loadByGUIDs(ManualJournal::class, $this->getAccountingIDs());
                }
            } else {
                $journals = $xero->load(ManualJournal::class)->page($this->getPage())->execute();
            }
            $response = $journals;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetManualJournalResponse($this, $data);
    }
}
