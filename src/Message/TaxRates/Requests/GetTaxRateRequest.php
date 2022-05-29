<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\TaxRates\Responses\GetTaxRateResponse;
use XeroPHP\Application;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Models\Accounting\TaxType;
use XeroPHP\Remote\Exception\UnauthorizedException;
use Calcinai\OAuth2\Client\Provider\Exception\XeroProviderException;
use XeroPHP\Remote\Exception\BadRequestException;
use XeroPHP\Remote\Exception\ForbiddenException;
use XeroPHP\Remote\Exception\ReportPermissionMissingException;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\InternalErrorException;
use XeroPHP\Remote\Exception\NotImplementedException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use XeroPHP\Remote\Exception\NotAvailableException;
use XeroPHP\Remote\Exception\OrganisationOfflineException;
/**
 * Get Tax Rate(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class GetTaxRateRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (TaxRateID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (QuoteID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }
    /**
     * Set AccountingID from Parameter Bag (TaxRateID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Set SearchFilters from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     * @return GetTaxRateRequest
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
     * @return GetTaxRateRequest
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
     * @return GetTaxRateRequest
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
     * @return GetTaxRateRequest
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
     * Return Comma Delimited String of Accounting IDs (TaxRateIDs)
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
     * Builds search / filter query based on search parameters and filters
     * @param Application $xero
     * @return \XeroPHP\Remote\Query
     */
    private function buildSearchQuery(Application $xero) {
        // Set contains query for partial matching
        $query = $xero->load(TaxRate::class);
        $queryCounter = 0;
        if ($this->getSearchParams())
        {
            foreach($this->getSearchParams() as $key => $value)
            {
                if($this->getExactSearchValue())
                {
                    $searchQuery = $key.'="'.$value.'"';
                }
                else {
                    $searchQuery = $key.'.ToLower().Contains("'.strtolower($value).'")';
                }

                if ($queryCounter == 0)
                {
                    $query = $query->where($searchQuery);
                } else {
                    $query = $query->orWhere($searchQuery);
                }
                $queryCounter++;
            }
        }
        // If there are specific filters, add them to query
        $queryCounter = 0;
        if ($this->getSearchFilters())
        {
            foreach($this->getSearchFilters() as $key => $value)
            {
                $queryString = '';
                $filterKey = $key;
                foreach($value as $filterValue)
                {
                    $searchQuery = $filterKey.'="'.$filterValue.'"';
                    if ($queryCounter == 0)
                    {
                        $queryString = '('.$searchQuery;
                    } else {
                        if ($this->getMatchAllFilters())
                        {
                            $queryString.= ' AND '.$searchQuery;
                        }
                        else {
                            $queryString.= ' OR '.$searchQuery;
                        }
                    }
                    $queryCounter++;
                }
                $queryString.=")";
                $query->andWhere($queryString);
            }
        }
        return $query;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetTaxRateResponse
     * @throws \XeroPHP\Exception
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingID()) {
                $taxes = $xero->loadByGUID(TaxRate::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $taxes = $xero->loadByGUID(TaxRate::class, $this->getAccountingIDs());
                }
                else {
                    $taxes = $xero->loadByGUIDs(TaxRate::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = $this->buildSearchQuery($xero);
                    $taxes = $query->execute();
                } else {
                    $taxes = $xero->load(TaxRate::class)->execute();
                }
            }
            $response = $taxes;

        } catch (BadRequestException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'BadRequest',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (UnauthorizedException|XeroProviderException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Unauthorized',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ForbiddenException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Forbidden',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ReportPermissionMissingException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'ReportPermissionMissingException',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotFoundException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotFound',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (InternalErrorException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Internal',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotImplementedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotImplemented',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (RateLimitExceededException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'RateLimitExceeded',
                'rate_problem' => $exception->getRateLimitProblem(),
                'retry' => $exception->getRetryAfter(),
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotAvailableException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotAvailable',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (OrganisationOfflineException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'OrganisationOffline',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetTaxRateResponse($this, $data);
    }
}
