<?php
namespace PHPAccounting\Xero\Message;

use Omnipay\Common\Message\ResponseInterface;
use XeroPHP\Application\PartnerApplication;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Application\PublicApplication;

class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $xeroInstance;

    protected $data = [];

    /**
     * Get Access Token
     */

    public function getAccessToken(){
        return $this->getParameter('accessToken');
    }

    public function setXeroConfig($value){
        return $this->setParameter('xeroConfig', $value);
    }

    public function getXeroConfig(){
        return $this->getParameter('xeroConfig');
    }

    public function getAccessTokenSecret() {
        return $this->getParameter('accessTokenSecret');
    }

    public function setAccessTokenSecret($value) {
        return $this->setParameter('accessTokenSecret', $value);
    }

    protected function createXeroApplication(){
        $value  = $this->getXeroConfig();
        $type = $value['type'];
        switch ($type) {
            case "private":
                $this->xeroInstance = new PrivateApplication($value['config']);
                break;
            case "public":
                $this->xeroInstance = new PublicApplication($value['config']);
                break;
            case "partner":
                $this->xeroInstance = new PartnerApplication($value['config']);
                break;
            default:
                throw new \Exception('Application type must be set');
        }
        return $this->xeroInstance;
    }

    public function getXeroInstance(){
        return $this->xeroInstance;
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

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        parent::sendData($data);
        // TODO: Implement sendData() method.
    }
}