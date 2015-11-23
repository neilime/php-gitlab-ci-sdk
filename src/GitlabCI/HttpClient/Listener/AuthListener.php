<?php

namespace GitlabCI\HttpClient\Listener;

class AuthListener implements \Buzz\Listener\ListenerInterface
{

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $gitlabUrl;

    /**
     * Constructor
     * @param string $sToken
     * @param string $sGitlabUrl
     * @param string $sMethod
     */
    public function __construct($sToken = null, $sGitlabUrl = null, $sMethod = null)
    {
        if ($sMethod) {
            $this->setMethod($sMethod);
        }
        if ($sToken) {
            $this->setToken($sToken);
        }
        if ($sGitlabUrl) {
            $this->setGitlabUrl($sGitlabUrl);
        }
    }

    /**
     * @param \Buzz\Message\RequestInterface $oRequest
     */
    public function preSend(\Buzz\Message\RequestInterface $oRequest)
    {
        // Skip by default
        if (!$this->hasMethod()) {
            return;
        }

        switch ($this->getMethod()) {
            case \GitlabCI\Client::AUTH_HTTP_TOKEN:
                $oRequest->addHeader('PRIVATE-TOKEN: ' . $this->getToken());
                $sUrl = $oRequest->getUrl();
                $aQuery = array('url' => $this->getGitlabUrl());
                $sUrl .= (false === strpos($sUrl, '?') ? '?' : '&') . utf8_encode(http_build_query($aQuery, '', '&'));
                $oRequest->fromUrl(new \Buzz\Util\Url($sUrl));
                break;
            case \GitlabCI\Client::AUTH_URL_TOKEN:
                $aQuery = array(
                    'private_token' => $this->getToken(),
                    'url' => $this->getGitlabUrl()
                );
                $sUrl .= (false === strpos($sUrl, '?') ? '?' : '&') . utf8_encode(http_build_query($aQuery, '', '&'));
                $oRequest->fromUrl(new \Buzz\Util\Url($sUrl));
                break;
        }
    }

    /**
     * @return string
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getMethod()
    {
        if ($this->hasMethod()) {
            return $this->method;
        }
        throw new \GitlabCI\Exception\LogicException('Method expects a string, "' . gettype($this->method) . '" defined');
    }

    /**
     * @return boolean
     */
    public function hasMethod()
    {
        return is_string($this->method);
    }

    /**
     * @param string $sMethod
     * @return \GitlabCI\HttpClient\Listener\AuthListener
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setMethod($sMethod)
    {
        if (is_string($sMethod)) {
            $this->method = $sMethod;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Method expects a string, "' . gettype($sMethod) . '" given');
    }

    /**
     * @return string
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getGitlabUrl()
    {
        if (is_string($this->gitlabUrl)) {
            return $this->gitlabUrl;
        }
        throw new \GitlabCI\Exception\LogicException('Gitlab url expects a string, "' . gettype($this->gitlabUrl) . '" defined');
    }

    /**
     * @param string $sGitlabUrl
     * @return \GitlabCI\HttpClient\Listener\AuthListener
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setGitlabUrl($sGitlabUrl)
    {
        if (is_string($sGitlabUrl)) {
            $this->gitlabUrl = $sGitlabUrl;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('GitlabUrl expects a string, "' . gettype($sGitlabUrl) . '" given');
    }

    /**
     * @return string
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getToken()
    {
        if (is_string($this->token)) {
            return $this->token;
        }
        throw new \GitlabCI\Exception\LogicException('Token expects a string, "' . gettype($this->token) . '" defined');
    }

    /**
     * @param string $sToken
     * @return \GitlabCI\HttpClient\Listener\AuthListener
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setToken($sToken)
    {
        if (is_string($sToken)) {
            $this->token = $sToken;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Token expects a string, "' . gettype($sToken) . '" given');
    }

}
