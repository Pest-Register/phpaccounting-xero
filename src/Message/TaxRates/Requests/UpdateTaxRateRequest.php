<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\TaxRates\Requests\Traits\TaxRateRequestTrait;
use PHPAccounting\Xero\Message\TaxRates\Responses\UpdateTaxRateResponse;
use PHPAccounting\Xero\Message\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Remote\Exception;

/**
 * Update Inventory Item(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class UpdateTaxRateRequest extends AbstractXeroRequest
{
    use TaxRateRequestTrait, AccountingIDRequestTrait;

    public string $model = 'TaxRate';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('name', 'tax_type_id', 'tax_components', 'status', 'report_tax_type');
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
     * @return UpdateTaxRateResponse
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
     * @return UpdateTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateTaxRateResponse($this, $data);
    }
}
