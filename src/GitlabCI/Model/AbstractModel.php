<?php

namespace GitlabCI\Model;

abstract class AbstractModel
{

    /**
     * @var array
     */
    protected static $properties;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var  \GitlabCI\Client
     */
    protected $client;

    /**
     * @return \GitlabCI\Client
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getClient()
    {
        if ($this->client instanceof \GitlabCI\Client) {
            return $this->client;
        }
        throw new \GitlabCI\Exception\LogicException('Client expects an instance of "\GitlabCI\Client", "' . (is_object($this->client) ? get_class($this->client) : gettype($this->client)) . '" defined');
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @return \GitlabCI\Model\AbstractModel
     */
    public function setClient(\GitlabCI\Client $oClient)
    {
        $this->client = $oClient;
        return $this;
    }

    /**
     * @param string $sApiName
     * @return \GitlabCI\Api\ApiInterface
     */
    public function api($sApiName)
    {
        return $this->getClient()->api($sApiName);
    }

    /**
     * @param array $aData
     * @return \GitlabCI\Model\AbstractModel
     */
    public function hydrate(array $aData = array())
    {
        if ($aData) {
            foreach ($aData as $sKey => $sValue) {
                if (in_array($sKey, static::$properties)) {
                    $this->data[$sKey] = $sValue;
                }
            }
        }
        return $this;
    }

    /**
     * @param string $sProperty
     * @param mixed $sValue
     * @return \GitlabCI\Model\AbstractModel
     * @throws \GitlabCI\Exception\RuntimeException
     */
    public function __set($sProperty, $sValue)
    {
        if (!in_array($sProperty, static::$properties)) {
            throw new \GitlabCI\Exception\RuntimeException(sprintf(
                    'Property "%s" does not exist for %s object', $sProperty, get_called_class()
            ));
        }

        $this->data[$sProperty] = $sValue;
        return $this;
    }

    /**
     * @param string $sProperty
     * @return mixed
     * @throws \GitlabCI\Exception\RuntimeException
     */
    public function __get($sProperty)
    {
        if (!in_array($sProperty, static::$properties)) {
            throw new \GitlabCI\Exception\RuntimeException(sprintf(
                    'Property "%s" does not exist for %s object', $sProperty, get_called_class()
            ));
        }

        if (isset($this->data[$sProperty])) {
            return $this->data[$sProperty];
        }

        return null;
    }

}
