<?php

namespace GitlabCI\Model;

class Build extends \GitlabCI\Model\AbstractModel
{

    /**
     * @var array
     */
    protected static $properties = array(
        'id',
        'commands',
        'path',
        'ref',
        'sha',
        'build_id',
        'repo_url',
        'before_sha'
    );

    /**
     * Constructor
     * @param type $sBuildId
     * @param \GitlabCI\Client $oClient
     */
    public function __construct($sBuildId = null, \GitlabCI\Client $oClient = null)
    {
        if ($sBuildId) {
            $this->setBuildId($sBuildId);
        }
        if ($oClient) {
            $this->setClient($oClient);
        }
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param array $aData
     * @return \GitlabCI\Model\Build
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public static function fromArray(\GitlabCI\Client $oClient, array $aData)
    {
        if (empty($aData['id'])) {
            throw new \GitlabCI\Exception\InvalidArgumentException('Data "id" is empty');
        }
        $oBuild = new static($aData['id']);
        $oBuild->setClient($oClient);
        return $oBuild->hydrate($aData);
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param array $aParams
     * @return \GitlabCI\Model\Build
     */
    public static function update(\GitlabCI\Client $oClient, array $aParams = array())
    {
        $aData = $oClient->api('builds')->update($this->getBuildId(), $aParams);
        return static::fromArray($oClient, $aData);
    }

    /**
     * @return \GitlabCI\Model\Build
     */
    public function show()
    {
        $aData = $this->api('builds')->show($this->getBuildId());
        return static::fromArray($this->getClient(), $aData);
    }

    /**
     * @return scalar
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getBuildId()
    {
        if (is_scalar($this->buildId)) {
            return $this->buildId;
        }
        throw new \GitlabCI\Exception\LogicException('Build id expects a scalar value, "' . gettype($this->buildId) . '" defined');
    }

    /**
     * @param scalar $sBuildId
     * @return \GitlabCI\Model\Build
     */
    public function setBuildId($sBuildId)
    {
        if (is_scalar($sBuildId)) {
            $this->buildId = $sBuildId;
            return $this;
        }
        throw new \GitlabCI\Exception\LogicException('Build id expects a scalar value, "' . gettype($sBuildId) . '" goven');
    }

}
