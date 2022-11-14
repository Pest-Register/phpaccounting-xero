<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\TaxRates\Responses\GetTaxRateResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Remote\Exception;

/**
 * Get Tax Rate(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class GetTaxRateRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'TaxRate';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetTaxRateResponse
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
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        TaxRate::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    $taxes = $query->execute();
                } else {
                    $taxes = $xero->load(TaxRate::class)->execute();
                }
            }
            $response = $taxes;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
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

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
