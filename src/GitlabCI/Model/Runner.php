<?php

namespace GitlabCI\Model;

class Runner extends \GitlabCI\Model\AbstractModel
{

    /**
     * @var array
     */
    protected static $properties = array(
        'id',
        'token',
    );

    /**
     * Constructor
     * @param type $sRunnerId
     * @param \GitlabCI\Client $oClient
     */
    public function __construct($sRunnerId = null, \GitlabCI\Client $oClient = null)
    {
        if ($sRunnerId) {
            $this->setRunnerId($sRunnerId);
        }
        if ($oClient) {
            $this->setClient($oClient);
        }
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param array $aData
     * @return \GitlabCI\Model\Runner
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public static function fromArray(\GitlabCI\Client $oClient, array $aData)
    {
        if (empty($aData['id'])) {
            throw new \GitlabCI\Exception\InvalidArgumentException('Data "id" is empty');
        }
        $oRunner = new static($aData['id']);
        $oRunner->setClient($oClient);
        return $oRunner->hydrate($aData);
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param scalar $sToken
     * @return \GitlabCI\Model\Runner
     */
    public static function register(\GitlabCI\Client $oClient, $sToken)
    {
        $aData = $oClient->api('runners')->update($this->getRunnerId(), $sToken);
        return static::fromArray($oClient, $aData);
    }

    /**
     * @return scalar
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getRunnerId()
    {
        if (is_scalar($this->runnerId)) {
            return $this->runnerId;
        }
        throw new \GitlabCI\Exception\LogicException('Runner id expects a scalar value, "' . gettype($this->runnerId) . '" defined');
    }

    /**
     * @param scalar $sRunnerId
     * @return \GitlabCI\Model\Runner
     */
    public function setRunnerId($sRunnerId)
    {
        if (is_scalar($sRunnerId)) {
            $this->runnerId = $sRunnerId;
            return $this;
        }
        throw new \GitlabCI\Exception\LogicException('Runner id expects a scalar value, "' . gettype($sRunnerId) . '" goven');
    }

}
