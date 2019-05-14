<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:30 PM
 */

class AbstractRequest extends \PHPAccounting\Common\Message\AbstractRequest
{

    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $endpoint = 'https://api.xero.com/api.xro/2.0';


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
     * @param $dataArray
     * @param $xeroKey
     * @param $localkey
     */
    public function issetParam($dataArray, $xeroKey, $localkey){
        if(array_key_exists($localkey, $this->getParameters())){
            $dataArray[$xeroKey] = $this->getParameters()[$localkey];
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