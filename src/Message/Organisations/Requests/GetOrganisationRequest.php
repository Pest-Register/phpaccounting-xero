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