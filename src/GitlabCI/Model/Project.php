<?php

namespace GitlabCI\Model;

class Project extends \GitlabCI\Model\AbstractModel
{

    /**
     * @var array
     */
    protected static $properties = array(
        'id',
        'code',
        'name',
        'name_with_namespace',
        'namespace',
        'description',
        'path',
        'path_with_namespace',
        'ssh_url_to_repo',
        'http_url_to_repo',
        'web_url',
        'default_branch',
        'owner',
        'private',
        'public',
        'issues_enabled',
        'merge_requests_enabled',
        'wall_enabled',
        'wiki_enabled',
        'created_at',
        'greatest_access_level',
        'last_activity_at',
        'snippets_enabled'
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
        if (is_scalar($this->projectId)) {
            return $this->projectId;
        }
        throw new \GitlabCI\Exception\LogicException('ProjectId expects a scalar value, "' . gettype($this->projectId) . '" defined');
    }

    /**
     * @param scalar $sProjectId
     * @return \GitlabCI\Model\Project
     */
    public function setProjectId($sProjectId)
    {
        if (is_scalar($sProjectId)) {
            $this->projectId = $sProjectId;
            return $this;
        }
        throw new \GitlabCI\Exception\LogicException('ProjectId expects a scalar value, "' . gettype($sProjectId) . '" goven');
    }

}
