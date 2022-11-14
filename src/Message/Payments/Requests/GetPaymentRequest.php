<?php

namespace PHPAccounting\Xero\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Payments\Responses\GetPaymentResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Remote\Exception;

/**
 * Get Invoice(s)
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class GetPaymentRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'Payment';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetPaymentResponse
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
                $payments = $xero->loadByGUID(Payment::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $payments = $xero->loadByGUID(Payment::class, $this->getAccountingIDs());
                }
                else {
                    $payments = $xero->loadByGUIDs(Payment::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Payment::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    $payments = $query->page($this->getPage())->execute();
                } else {
                    $payments = $xero->load(Payment::class)->page($this->getPage())->execute();
                }
            }
            $response = $payments;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetPaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetPaymentResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
