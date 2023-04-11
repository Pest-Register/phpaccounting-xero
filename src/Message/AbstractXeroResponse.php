<?php

namespace PHPAccounting\Xero\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;

class AbstractXeroResponse extends AbstractResponse
{
    /**
     * Model type used for abstract parsing of errors and responses
     * @var string
     */
    private string $modelType;

    /**
     * Sets model type, request interface and data model
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->modelType = $request->model;
        parent::__construct($request, $data);
    }

    /**
     * Checks whether the response was successful
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if ($this->data instanceof \XeroPHP\Remote\Collection) {
                if (count($this->data) == 0) {
                    return false;
                }
            } elseif (is_array($this->data)) {
                if (count($this->data) == 0) {
                    return false;
                }
            }
            // Check if data is returned as an array
            if (is_array($this->data)){
                if (array_key_exists('status', $this->data)) {
                    return !$this->data['status'] == 'error';
                }
            }
            // Check if data is returned as an object
            if (is_object($this->data)){
                if(property_exists($this->data,'status')){
                    return !$this->data['status'] == 'error';
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Parse error message from provider
     * @return array
     */
    private function parseErrorMessage() {
        return ErrorResponseHelper::parseErrorResponse(
            $this->data['detail'] ?? null,
            $this->data['type'] ?? null,
            $this->data['status'] ?? null,
            $this->data['error_code'] ?? null,
            $this->data['status_code'] ?? null,
            $this->data['detail'] ?? null,
            $this->data,
            $this->modelType);
    }

    /**
     * Fetch Error Message from Response
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if (count($this->data) === 0) {
                return [
                    'message' => 'NULL Returned from API or End of Pagination',
                    'exception' => 'NULL Returned from API or End of Pagination',
                    'error_code' => null,
                    'status_code' => null,
                    'detail' => null
                ];
            }
            // Safeguard for arrays and objects, check is status was returned indicating well-formed
            // error message
            if (is_array($this->data)) {
                if (array_key_exists('status', $this->data)) {
                    return $this->parseErrorMessage();
                }
            }
            else if (is_object($this->data)) {
                if (property_exists($this->data, 'status')) {
                    return $this->parseErrorMessage();
                }
            }
        }
        return null;
    }
}