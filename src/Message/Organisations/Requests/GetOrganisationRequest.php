<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:07 AM
 */

namespace PHPAccounting\Xero\Message\Organisations\Requests;


use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Organisations\Responses\GetOrganisationResponse;
use XeroPHP\Models\Accounting\Organisation;

class GetOrganisationRequest extends AbstractRequest
{


    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetOrganisationResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $response = $xero->load(Organisation::class)->execute();
        } catch (\Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetOrganisationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetOrganisationResponse($this, $data);
    }
}