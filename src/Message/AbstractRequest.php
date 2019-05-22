<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:30 PM
 */

namespace PHPAccounting\Xero\Message;

class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $endpoint = 'https://api.Xero.com/api.xro/2.0';

    protected $data = [];

    /**
     * Get the gateway API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Get Access Token
     */

    public function getAccessToken(){
        return $this->getParameter('accessToken');
    }

    /**
     * Set Access Token
     * @param $value
     * @return AbstractRequest
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
     * @return array
     */
    public function getHeaders()
    {
        $headers = array();

        $headers['oauth_consumer_key'] = 'LEFVEZ26CAJQXOBLKNZGE5KDAY2HP3';
        $headers['oauth_token'] = $this->getAccessToken();
        return $headers;
    }
    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $headers = array_merge(
            $this->getHeaders(),
            array('Authorization' => 'Basic ' . base64_encode($this->getAccessToken() . ':'))
        );
        $body = $data ? http_build_query($data, '', '&') : null;
        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);
        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());
    }

    protected function createResponse($data, $headers = [])
    {
        return $this->response = new Response($this, $data, $headers);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        // TODO: Implement getData() method.
    }
}