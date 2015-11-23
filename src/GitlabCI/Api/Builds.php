<?php

namespace GitlabCI\Api;

class Builds extends \GitlabCI\Api\AbstractApi
{

    /**
     * @param scalar $sToken
     * @return array
     * @throws \InvalidArgumentException
     */
    public function register($sToken)
    {
        if (is_scalar($sToken)) {
            return $this->post('builds/register', array(
                        'token' => $sToken,
            ));
        }
        throw new \InvalidArgumentException('Token expects a scalar value, "' . gettype($sToken) . '" given');
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
            return $this->put('builds/' . urlencode($sProjectId), $aParams);
        }
        throw new \InvalidArgumentException('Project id expects a scalar value, "' . gettype($sProjectId) . '" given');
    }

}
