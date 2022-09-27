<?php
namespace PHPAccounting\Xero\Message;

use Calcinai\OAuth2\Client\Provider\Xero;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use XeroPHP\Application;
use XeroPHP\Remote\Exception\RateLimitExceededException;

abstract class AbstractXeroRequest extends AbstractRequest
{

    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $xeroInstance;

    protected $data = [];

    abstract public function sendData($data);

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    abstract public function getData();

    /**
     * Get Access Token
     */

    public function getAccessToken(){
        return $this->getParameter('accessToken');
    }

    public function setTenantID($value) {
        return $this->setParameter('tenantID', $value);
    }

    public function getTenantID() {
        return $this->getParameter('tenantID');
    }

    public function getClientID() {
        return $this->getParameter('tenantID');
    }

    public function setClientID($value) {
        return $this->setParameter('clientID', $value);
    }

    public function getClientSecret() {
        return $this->getParameter('tenantID');
    }

    public function setClientSecret($value) {
        return $this->setParameter('clientID', $value);
    }

    public function getCallbackURL() {
        return $this->getParameter('callbackURL');
    }

    public function setCallbackURL($value) {
        return $this->setParameter('callbackURL', $value);
    }

    protected function createXeroApplication(){
        $this->xeroInstance = new Application($this->getAccessToken(), $this->getTenantID());
        return $this->xeroInstance;
    }

    protected function createProviderForTenants() {
        $provider = new Xero([
            'clientId'          => $this->getClientID(),
            'clientSecret'      => $this->getClientSecret(),
            'redirectUri'       => $this->getCallbackURL(),
        ]);

        return $provider;
    }


    public function getXeroInstance(){
        return $this->xeroInstance;
    }

    /**
     * Set Access Token
     * @param $value
     * @return AbstractXeroRequest
     */

    public function setAccessToken($value){
        return $this->setParameter('accessToken', $value);
    }

    /**
     * Check if key exists in param bag and add it to array
     * @param $XeroKey
     * @param $localKey
     */
    public function issetParam($XeroKey, $localKey){
        if(array_key_exists($localKey, $this->getParameters())){
            $this->data[$XeroKey] = $this->getParameter($localKey);
        }
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Handle exception messages, codes and additional details
     * @param $exception
     * @param $type
     * @return array
     */
    public function handleRequestException($exception, $type): array
    {
        $response = [
            'status' => 'error',
            'type' => $type,
            'detail' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
            'status_code' => $exception->getCode(),
        ];
        if ($type == RateLimitExceededException::class) {
            $response['rate_problem'] = $exception->getRateLimitProblem();
            $response['retry'] = $exception->getRetryAfter();
        }
        return $response;
    }
}