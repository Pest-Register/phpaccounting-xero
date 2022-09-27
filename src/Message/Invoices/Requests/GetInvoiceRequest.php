<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use PHPAccounting\Xero\Message\Invoices\Responses\GetInvoiceResponse;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Remote\Exception;

use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
/**
 * Get Invoice(s)
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class GetInvoiceRequest extends AbstractXeroRequest
{
    public string $model = 'Invoice';

    /**
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetInvoiceRequest
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
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (ContactGroupIDs)
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
     * Set SearchFilters from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setSearchFilters($value) {
        return $this->setParameter('search_filters', $value);
    }

    /**
     * Return Search Filters for query-based searching
     * @return array
     */
    public function getSearchFilters() {
        return $this->getParameter('search_filters');
    }

    /**
     * Set SearchParams from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setSearchParams($value) {
        return $this->setParameter('search_params', $value);
    }

    /**
     * Return Search Parameters for query-based searching
     * @return array
     */
    public function getSearchParams() {
        return $this->getParameter('search_params');
    }

    /**
     * Set boolean to determine partial or exact query based searches
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setExactSearchValue($value) {
        return $this->setParameter('exact_search_value', $value);
    }

    /**
     * Get boolean to determine partial or exact query based searches
     * @return mixed
     */
    public function getExactSearchValue() {
        return $this->getParameter('exact_search_value');
    }

    /**
     * Set boolean to determine whether all filters need to be matched
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setMatchAllFilters($value) {
        return $this->setParameter('match_all_filters', $value);
    }

    /**
     * Get boolean to determine whether all filters need to be matched
     * @return mixed
     */
    public function getMatchAllFilters() {
        return $this->getParameter('match_all_filters');
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetContactResponse
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
                $invoices = $xero->loadByGUID(Invoice::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $invoices = $xero->loadByGUID(Invoice::class, $this->getAccountingIDs());
                }
                else {
                    $invoices = $xero->loadByGUIDs(Invoice::class, $this->getAccountingIDs());
                 }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Invoice::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    $invoices = $query->page($this->getPage())->execute();
                } else {
                    $invoices = $xero->load(Invoice::class)->page($this->getPage())->execute();
                }
            }
            $response = $invoices;

        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInvoiceResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
