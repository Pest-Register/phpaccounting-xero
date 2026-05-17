<?php

namespace PHPAccounting\Xero\Message;

use PHPAccounting\Xero\Foundation\AbstractResponse;
use PHPAccounting\Xero\Foundation\Contracts\RequestInterface;
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
    public function isSuccessful(): bool
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
                    return $this->data['status'] !== 'error';
                }
            }
            // Check if data is returned as an object
            if (is_object($this->data)){
                if(property_exists($this->data,'status')){
                    return $this->data->status !== 'error';
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
     * Return Xero's `xero-correlation-id` response header when available.
     *
     * The current XeroPHP application (calcinai/xero-php) parses entities and
     * returns model objects directly from $xero->load(...)->execute(); the raw
     * HTTP response (and therefore its headers) is not surfaced by the SDK at
     * the point this response is constructed. The stub is here so callers
     * (Mira's RequestContext plumbing) can rely on a consistent
     * Abstract*Response::getTraceId() contract across providers. Returning the
     * real value requires either:
     *  (a) extending XeroPHP to expose response headers, or
     *  (b) capturing the header in handleRequestException for the error path.
     *
     * @return string|null
     */
    public function getTraceId()
    {
        return null;
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