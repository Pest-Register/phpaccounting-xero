<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:07 AM
 */

namespace PHPAccounting\Xero\Message\Organisations\Requests;

use League\OAuth2\Client\Token\AccessToken;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Organisations\Responses\GetOrganisationResponse;
use XeroPHP\Models\Accounting\Organisation;
use XeroPHP\Remote\Exception;

class GetOrganisationRequest extends AbstractXeroRequest
{
    public string $model = 'Organisation';

    private function parseOrganisationResponse($data){
        $orgs = [];
        foreach($data as $test) {
            array_push($orgs, $test);
        }
        return $orgs;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetOrganisationResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            if ($this->getTenantID()) {
                $xero = $this->createXeroApplication();
                $response = $xero->load(Organisation::class)->execute();
            } else {
                $temp = $this->createProviderForTenants();
                $token = new AccessToken(array('access_token' => $this->getAccessToken()));
                $tenantResponse = $temp->getTenants($token);
                $response = [];
                foreach ($tenantResponse as $tenant) {
                    $this->setTenantID($tenant->tenantId);
                    $xero = $this->createXeroApplication();
                    $orgResponse = $xero->load(Organisation::class)->execute();
                    $response = array_merge($response, $this->parseOrganisationResponse($orgResponse));
                }
            }
        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
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
