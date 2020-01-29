<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 9/09/2019
 * Time: 4:41 PM
 */

namespace PHPAccounting\Xero\Message\ManualJournals\Requests;


use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ManualJournals\Responses\DeleteManualJournalResponse;
use XeroPHP\Models\Accounting\ManualJournal;

class DeleteManualJournalRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return DeleteManualJournalRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Contact Name
     * @return DeleteManualJournalRequest
     */
    public function setStatus($value) {
        return  $this->setParameter('status', $value);
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
        $this->validate('accounting_id');
        $this->issetParam('ManualJournalID', 'accounting_id');
        $this->issetParam('Status', 'status');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteManualJournalResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            $invoice = new ManualJournal($xero);
            foreach ($data as $key => $value){
                $methodName = 'set'. $key;
                $invoice->$methodName($value);
            }

            $response = $invoice->save();

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
     * @return DeleteManualJournalResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteManualJournalResponse($this, $data);
    }
}