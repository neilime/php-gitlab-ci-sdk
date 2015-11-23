<?php

namespace GitlabCI\Model;

class Project extends \GitlabCI\Model\AbstractModel
{

    /**
     * @var array
     */
    protected static $properties = array(
        'id',
		'name',
		'timeout',
		'scripts',
		'token',
		'default_ref',
		'gitlab_url',
		'always_build',
		'polling_interval',
		'public',
		'ssh_url_to_repo',
		'gitlab_id',
    );

    /**
     * Constructor
     * @param type $sProjectId
     * @param \GitlabCI\Client $oClient
     */
    public function __construct($sProjectId = null, \GitlabCI\Client $oClient = null)
    {
        if ($sProjectId) {
            $this->setProjectId($sProjectId);
        }
        if ($oClient) {
            $this->setClient($oClient);
        }
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param array $aData
     * @return \GitlabCI\Model\Project
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public static function fromArray(\GitlabCI\Client $oClient, array $aData)
    {
        if (empty($aData['id'])) {
            throw new \GitlabCI\Exception\InvalidArgumentException('Data "id" is empty');
        }
        $oProject = new static($aData['id']);
        $oProject->setClient($oClient);
        return $oProject->hydrate($aData);
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param string $sName
     * @param array $aParams
     * @return \GitlabCI\Model\Project
     */
    public static function create(\GitlabCI\Client $oClient, $sName, array $aParams = array())
    {
        $aData = $oClient->api('projects')->create($sName, $aParams);

        return static::fromArray($oClient, $aData);
    }

    /**
     * @param \GitlabCI\Client $oClient
     * @param array $aParams
     * @return \GitlabCI\Model\Project
     */
    public static function update(\GitlabCI\Client $oClient, array $aParams = array())
    {
        $aData = $oClient->api('builds')->update($this->getProjectId(), $aParams);
        return static::fromArray($oClient, $aData);
    }

    /**
     * @return \GitlabCI\Model\Project
     */
    public function show()
    {
        $aData = $this->api('projects')->show($this->getProjectId());
        return static::fromArray($this->getClient(), $aData);
    }

    /**
     * @return \GitlabCI\Model\Project
     */
    public function remove()
    {
        $this->api('projects')->remove($this->getProjectId());
        return $this;
    }

    /**
     * @return scalar
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getProjectId()
    {
        if (is_scalar($this->data['id'])) {
            return $this->data['id'];
        }
        throw new \GitlabCI\Exception\LogicException('Project id expects a scalar value, "' . gettype($this->data['id']) . '" defined');
    }

    /**
     * @param scalar $sProjectId
     * @return \GitlabCI\Model\Project
     */
    public function setProjectId($sProjectId)
    {
        if (is_scalar($sProjectId)) {
            $this->data['id'] = $sProjectId;
            return $this;
        }
        throw new \GitlabCI\Exception\LogicException('ProjectId expects a scalar value, "' . gettype($sProjectId) . '" gven');
    }

}
