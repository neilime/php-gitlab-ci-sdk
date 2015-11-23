<?php

namespace GitlabCI\HttpClient\Listener;

class ErrorListener implements \Buzz\Listener\ListenerInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor
     * @param array $aOptions
     */
    public function __construct(array $aOptions = array())
    {
        if ($aOptions) {
            $this->setOptions($aOptions);
        }
    }

    /**
     * @param \Buzz\Message\RequestInterface $oRequest
     * @param \Buzz\Message\MessageInterface $oResponse
     * @throws \GitlabCI\Exception\ErrorException
     * @throws \GitlabCI\Exception\RuntimeException
     */
    public function postSend(\Buzz\Message\RequestInterface $oRequest, \Buzz\Message\MessageInterface $oResponse)
    {
        if ($oResponse->isClientError() || $oResponse->isServerError()) {
            $aContent = $oResponse->getContent();
            if (is_array($aContent) && isset($aContent['message'])) {
                if (400 == $oResponse->getStatusCode()) {
                    throw new \GitlabCI\Exception\ErrorException($aContent['message'], 400);
                }
            }

            $sErrorMessage = null;
            if (isset($aContent['error'])) {
                $sErrorMessage = implode("\n", $aContent['error']);
            } elseif (isset($aContent['message'])) {
                $sErrorMessage = $aContent['message'];
            } else {
                $sErrorMessage = $aContent;
            }

            throw new \GitlabCI\Exception\RuntimeException($sErrorMessage, $oResponse->getStatusCode());
        }
    }

    /**
     * @return array
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getOptions()
    {
        if (is_array($this->options)) {
            return $this->options;
        }
        throw new \GitlabCI\Exception\LogicException('Options expects an array, "' . gettype($this->options) . '" defined');
    }

    /**
     * @param array $aOptions
     * @return \GitlabCI\HttpClient\Listener\ErrorListener
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setOptions(array $aOptions)
    {
        if (is_array($aOptions)) {
            $this->options = $aOptions;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Options expects an array, "' . gettype($sOptions) . '" given');
    }

}
