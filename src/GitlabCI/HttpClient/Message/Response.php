<?php

namespace GitlabCI\HttpClient\Message;

class Response extends \Buzz\Message\Response
{

    /**
     * @return string
     */
    public function getContent()
    {
        $sResponse = parent::getContent();
        if ($this->getHeader('Content-Type') === 'application/json') {
            $sContent = json_decode($sResponse, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                return $sResponse;
            }

            return $sContent;
        } else {
            return $sResponse;
        }
    }

}
