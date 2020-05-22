<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\TaxRates\Responses\CreateTaxRateResponse;
use XeroPHP\Models\Accounting\TaxRate;
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
 * Create Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class CreateTaxRateRequest extends AbstractRequest
{
    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @return mixed
     */
    public function getName(){
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @param string $value Tax Rate Name
     * @return CreateTaxRateRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Get Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @return mixed
     */
    public function getTaxType(){
        return $this->getParameter('tax_type');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @param string $value Tax Rate Tax Type
     * @return CreateTaxRateRequest
     */
    public function setTaxType($value){
        return $this->setParameter('tax_type', $value);
    }

    /**
     * Get Tax Components Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @return mixed
     */
    public function getTaxComponents(){
        return $this->getParameter('tax_components');
    }

    /**
     * Set Tax Components Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @param string $value Tax Rate Tax Type
     * @return CreateTaxRateRequest
     */
    public function setTaxComponents($value){
        return $this->setParameter('tax_components', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Tax Components Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @param string $value Status
     * @return CreateTaxRateRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Report Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @return mixed
     */
    public function getReportTaxType(){
        return $this->getParameter('report_tax_type');
    }

    /**
     * Set Report Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/tax-rates
     * @param string $value Report Tax Type
     * @return CreateTaxRateRequest
     */
    public function setReportTaxType($value){
        return $this->setParameter('report_tax_type', $value);
    }

    /**
     * @param TaxRate $taxRate
     * @param $taxComponents
     */
    public function addTaxComponentsToTaxRate(TaxRate $taxRate, $taxComponents) {
        if ($taxComponents) {
            $taxComponent = new TaxRate\TaxComponent();
            $taxComponent->setName($taxComponents['name']);
            $taxComponent->setRate($taxComponents['rate']);
            $taxComponent->setIsCompound($taxComponents['is_compound']);
            $taxRate->addTaxComponent($taxComponent);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getTaxComponentsDetails($data){
        $data['Name'] = IndexSanityCheckHelper::indexSanityCheck('name', $data);
        $data['Rate'] = IndexSanityCheckHelper::indexSanityCheck('rate', $data);
        $data['IsCompound'] = IndexSanityCheckHelper::indexSanityCheck('is_compound', $data);
        $data['IsNonRecoverable'] = IndexSanityCheckHelper::indexSanityCheck('is_non_recoverable', $data);

        return $data;
    }
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->issetParam('Name', 'name');
        $this->issetParam('TaxType', 'tax_type');
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
        } catch (BadRequestException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'BadRequest',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (UnauthorizedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Unauthorized',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (ForbiddenException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Forbidden',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (ReportPermissionMissingException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'ReportPermissionMissingException',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotFoundException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotFound',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (InternalErrorException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Internal',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotImplementedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotImplemented',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (RateLimitExceededException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'RateLimitExceeded',
                'rate_problem' => $exception->getRateLimitProblem(),
                'retry' => $exception->getRetryAfter(),
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotAvailableException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotAvailable',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (OrganisationOfflineException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'OrganisationOffline',
                'detail' => $exception->getMessage()
            ];

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