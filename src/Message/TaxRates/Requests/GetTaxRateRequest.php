<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\TaxRates\Responses\GetTaxRateResponse;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Models\Accounting\TaxType;
use XeroPHP\Remote\Exception\UnauthorizedException;
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
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetTaxRateResponse
     * @throws \XeroPHP\Exception
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $taxes = $xero->loadByGUID(TaxRate::class, $this->getAccountingIDs());
                }
                else {
                    $taxes = $xero->loadByGUIDs(TaxRate::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams())
                {
                    // Set contains query for partial matching
                    $query = $xero->load(TaxRate::class);
                    $queryCounter = 0;
                    foreach($this->getSearchParams() as $key => $value)
                    {
                        $searchQuery = $key.'.ToLower().Contains("'.strtolower($value).'")';
                        if ($queryCounter == 0)
                        {
                            $query = $query->where($searchQuery);
                        } else {
                            $query = $query->orWhere($searchQuery);
                        }
                        $queryCounter++;
                    }
                    $taxes = $query->execute();
                } else {
                    $taxes = $xero->load(TaxRate::class)->page($this->getPage())->execute();
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
        } catch (UnauthorizedException $exception) {
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