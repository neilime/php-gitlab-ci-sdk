<?php

namespace GitlabCI\Api;

class Runners extends \GitlabCI\Api\AbstractApi
{

    /**
     * @return array
     */
    public function all()
    {
        return $this->get('runners');
    }

    /**
     * @param scalar $sToken
     * @param scalar $sPublicKey
     * @return array
     * @throws \InvalidArgumentException
     */
    public function register($sToken, $sPublicKey)
    {
        if (is_scalar($sToken)) {
            if (is_scalar($sPublicKey)) {
                return $this->post('runners/register', array(
                            'token' => $sToken,
                            'public_key' => $sPublicKey
                ));
            }
            throw new \InvalidArgumentException('Public key expects a scalar value, "' . gettype($sPublicKey) . '" given');
        }
        throw new \InvalidArgumentException('Token expects a scalar value, "' . gettype($sToken) . '" given');
    }

}
