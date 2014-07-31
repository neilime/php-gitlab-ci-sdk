<?php

namespace GitlabCI\Api;

abstract class AbstractApi implements \GitlabCI\Api\ApiInterface
{

    /**
     * @var \GitlabCI\Client
     */
    protected $client;

    /**
     * Constructor
     * @param \GitlabCI\Client $oClient
     */
    public function __construct(\GitlabCI\Client $oClient = null)
    {
        if ($oClient) {
            $this->setClient($oClient);
        }
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aRequestHeaders
     * @return type
     */
    protected function get($sPath, array $aParameters = array(), $aRequestHeaders = array())
    {
        return $this->getClient()->getHttpClient()->get($sPath, $aParameters, $aRequestHeaders)->getContent();
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aRequestHeaders
     * @return type
     */
    protected function post($sPath, array $aParameters = array(), $aRequestHeaders = array())
    {
        return $this->getClient()->getHttpClient()->post($sPath, $aParameters, $aRequestHeaders)->getContent();
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aRequestHeaders
     * @return type
     */
    protected function patch($sPath, array $aParameters = array(), $aRequestHeaders = array())
    {
        return $this->client->getHttpClient()->patch($sPath, $aParameters, $aRequestHeaders)->getContent();
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aRequestHeaders
     * @return type
     */
    protected function put($sPath, array $aParameters = array(), $aRequestHeaders = array())
    {
        return $this->client->getHttpClient()->put($sPath, $aParameters, $aRequestHeaders)->getContent();
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aRequestHeaders
     * @return type
     */
    protected function delete($sPath, array $aParameters = array(), $aRequestHeaders = array())
    {
        return $this->client->getHttpClient()->delete($sPath, $aParameters, $aRequestHeaders)->getContent();
    }

    /**
     * @return \GitlabCI\Client
     * @throws \LogicException
     */
    public function getClient()
    {
        if ($this->client instanceof \GitlabCI\Client) {
            return $this->client;
        }
        throw new \LogicException('Client expects an instance of "\GitlabCI\Client", "' . (is_object($this->client) ? get_class($this->client) : gettype($this->client)) . '" defined');
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @return \GitlabCI\Api\AbstractApi
     */
    public function setClient(\GitlabCI\Client $oClient)
    {
        $this->client = $oClient;
        return $this;
    }

}
