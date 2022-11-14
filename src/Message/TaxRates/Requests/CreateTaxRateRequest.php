<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\TaxRates\Requests\Traits\TaxRateRequestTrait;
use PHPAccounting\Xero\Message\TaxRates\Responses\CreateTaxRateResponse;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Remote\Exception;

/**
 * Create Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class CreateTaxRateRequest extends AbstractXeroRequest
{
    use TaxRateRequestTrait;

    public string $model = 'TaxRate';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->issetParam('Name', 'name');
        $this->issetParam('TaxType', 'tax_type_id');
        $this->issetParam('Status', 'status');
        $this->issetParam('ReportTaxType', 'report_tax_type');
        $this->data['TaxComponents'] = ($this->getTaxComponents() != null ? $this->getTaxComponentsDetails($this->getTaxComponents()) : null);

        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return CreateTaxRateResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();

            $taxRate = new TaxRate($xero);
            foreach ($data as $key => $value){
                if ($key === 'TaxComponents') {
                    $this->addTaxComponentsToTaxRate($taxRate, $value);
                } else {
                    $methodName = 'set'. $key;
                    $taxRate->$methodName($value);
                }

            }
            $response = $xero->save($taxRate);
        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateTaxRateResponse($this, $data);
    }
}
