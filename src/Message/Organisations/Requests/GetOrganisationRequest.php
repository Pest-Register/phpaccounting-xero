<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:07 AM
 */

namespace PHPAccounting\Xero\Message\Organisations\Requests;


use League\OAuth2\Client\Token\AccessToken;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Organisations\Responses\GetOrganisationResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Organisation;
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
class GetOrganisationRequest extends AbstractRequest
{
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