<?php

namespace GitlabCI\Api;

class Projects extends \GitlabCI\Api\AbstractApi
{

    /**
     * @return array
     */
    public function authorized()
    {
        return $this->get('projects');
    }

    /**
     * @return array
     */
    public function owned()
    {
        return $this->get('projects/owned');
    }

    /**
     * @param scalar $sProjectId
     * @return array
     * @throws \InvalidArgumentException
     */
    public function show($sProjectId)
    {
        if (is_scalar($sProjectId)) {
            return $this->get('projects/' . urlencode($sProjectId));
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

    /**
     * @param scalar $sName
     * @param array $aParams
     * @return array
     * @throws \InvalidArgumentException
     */
    public function create($sName, array $aParams = array())
    {
        if (is_scalar($sName)) {
            $aParams['name'] = $sName;
            return $this->post('projects', $aParams);
        }
        throw new \InvalidArgumentException('Name expects a scalar value, "' . gettype($sName) . '" given');
    }

    /**
     * @param scalar $sProjectId
     * @param array $aParams
     * @return array
     * @throws \InvalidArgumentException
     */
    public function update($sProjectId, array $aParams = array())
    {
        if (is_scalar($sProjectId)) {
            return $this->put('projects/' . urlencode($sProjectId), $aParams);
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

    /**
     * @param scalar $sProjectId
     * @return scalar
     * @throws \InvalidArgumentException
     */
    public function remove($sProjectId)
    {
        if (is_scalar($sProjectId)) {
            return $this->delete('projects/' . urlencode($sProjectId));
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

    /**
     * @param scalar $sProjectId
     * @param scalar $sRunnerId
     * @return array
     * @throws \InvalidArgumentException
     */
    public function linkToRunner($sProjectId, $sRunnerId)
    {
        if (is_scalar($sProjectId)) {
            if (is_scalar($sRunnerId)) {
                return $this->post('projects/' . urlencode($sProjectId) . '/runners/' . urlencode($sRunnerId));
            }
            throw new \InvalidArgumentException('Runner id expects a scalar value, "' . gettype($sRunnerId) . '" given');
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

    /**
     * @param scalar $sProjectId
     * @param scalar $sRunnerId
     * @return array
     * @throws \InvalidArgumentException
     */
    public function removeFromRunner($sProjectId, $sRunnerId)
    {
        if (is_scalar($sProjectId)) {
            if (is_scalar($sRunnerId)) {
                return $this->delete('projects/' . urlencode($sProjectId) . '/runners/' . urlencode($sRunnerId));
            }
            throw new \InvalidArgumentException('Runner id expects a scalar value, "' . gettype($sRunnerId) . '" given');
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

}
