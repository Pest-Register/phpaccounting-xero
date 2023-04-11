<?php


namespace PHPAccounting\Xero\Message\Quotations\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Quotations\Requests\Traits\QuotationRequestTrait;
use PHPAccounting\Xero\Message\Quotations\Responses\UpdateQuotationResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\LineItem;
use XeroPHP\Models\Accounting\Quote;
use XeroPHP\Remote\Exception;

class UpdateQuotationRequest extends AbstractXeroRequest
{
    use QuotationRequestTrait, AccountingIDRequestTrait;
    public string $model = 'Quotation';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('contact', 'quotation_data', 'accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('QuoteID', 'accounting_id');
        $this->issetParam('Date', 'date');
        $this->issetParam('ExpiryDate', 'expiry_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'quotation_data');
        $this->issetParam('QuoteNumber', 'quotation_number');
        $this->issetParam('Reference', 'quotation_reference');
        $this->issetParam('LineAmountType', 'gst_inclusive');
        $this->issetParam('Title', 'title');
        $this->issetParam('Summary', 'summary');
        $this->issetParam('Terms', 'terms');

        if ($this->getStatus()) {
            $this->data['Status'] = $this->parseStatus($this->getStatus());
        }
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|UpdateQuotationResponse
     * @throws \XeroPHP\Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();

            $quote = new Quote($xero);
            foreach ($data as $key => $value) {
                if ($key === 'LineItems') {
                    $this->addLineItemsToQuotation($quote, $value);
                } elseif ($key === 'Contact') {
                    $this->addContactToQuotation($quote, $value);
                } elseif ($key === 'Date' || $key === 'ExpiryDate') {
                    // If either date or expiry date are empty, Xero will set default values
                    $methodName = 'set'.$key;
                    if ($value) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value->toDateTimeString());
                        $quote->$methodName($date);
                    };
                } else if ($key === 'LineAmountType') {
                    $methodName = 'set'.$key;
                    if ($value === 'EXCLUSIVE') {
                        $quote->$methodName('Exclusive');
                    }
                    else if ($value === 'INCLUSIVE') {
                        $quote->$methodName('Inclusive');
                    } else {
                        $quote->$methodName('NoTax');
                    }
                } else if($key === 'Status') {
                    $methodName = 'set'.$key;
                    $quote->$methodName($value);
                } else {
                    $methodName = 'set'. $key;
                    $quote->$methodName($value);
                }
            }
            $response = $xero->save($quote);
        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateQuotationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateQuotationResponse($this, $data);
    }

}
