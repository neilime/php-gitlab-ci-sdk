<?php

namespace GitlabCI\Exception;

class MissingArgumentException extends ErrorException
{

    /**
     * @param array $aRequired
     * @param int $iCode
     * @param \Exception $oPrevious
     */
    public function __construct($aRequired, $iCode = 0, $oPrevious = null)
    {
        if (is_string($aRequired)) {
            $aRequired = array($aRequired);
        }

        parent::__construct(sprintf('One or more of required ("%s") parameters is missing!', implode('", "', $aRequired)), $iCode, $oPrevious);
    }

}
