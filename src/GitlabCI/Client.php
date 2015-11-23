<?php

namespace GitlabCI;

class Client
{

    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';

    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';

    /**
     * @var array
     */
    protected $options = array(
        'user_agent' => 'php-gitlab-ci-api (http://github.com/m4tthumphrey/php-gitlab-ci-api)',
        'timeout' => 60
    );

    /**
     * @var string
     */
    protected $baseUrl = null;

    /**
     * The Buzz instance used to communicate with Gitlab CI
     * @var \GitlabCI\HttpClient\HttpClient
     */
    private $httpClient;

    /**
     * Constructor
     * @param string $sBaseUrl
     * @param \Buzz\Client\ClientInterface $oHttpClient
     */
    public function __construct($sBaseUrl = null, \Buzz\Client\ClientInterface $oHttpClient = null)
    {
        if ($sBaseUrl) {
            $this->setBaseUrl($sBaseUrl);
        }

        if (!$oHttpClient) {
            $oHttpClient = new \Buzz\Client\Curl();
        }
        $oHttpClient->setTimeout($this->getOptions('timeout'));
        $oHttpClient->setVerifyPeer(false);

        $this->setHttpClient(new \GitlabCI\HttpClient\HttpClient($this->base_url, $this->options, $oHttpClient));
    }

    /**
     * @param string $sApiname
     * @return \GitlabCI\Api\ApiInterface
     * @throws \GitlabCI\Exception\DomainException
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function api($sApiname)
    {
        if (is_string($sApiname)) {
            switch ($sApiname) {
                case 'projects':
                    return new \GitlabCI\Api\Projects($this);
                case 'builders':
                    return new \GitlabCI\Api\Builders($this);
                case 'runners':
                    return new \GitlabCI\Api\Runners($this);
                default:
                    throw new \GitlabCI\Exception\DomainException('Api "' . $sApiname . '" is not supported');
            }
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Api name expects a string, "' . gettype($sApiname) . '" given');
    }

    /**
     * Authenticate a user for all next requests
     * @param string $sToken :  Gitlab CI private token
     * @param string $sGitlabUrl : the url of an authorized Gitlab instance
     * @param string $sMethod : one of the AUTH_* class constants
     */
    public function authenticate($sToken, $sGitlabUrl, $sMethod = self::AUTH_HTTP_TOKEN)
    {
        $this->getHttpClient()->addListener(new \GitlabCI\HttpClient\Listener\AuthListener($sToken, $sGitlabUrl, $sMethod));
    }

    /**
     * @return string
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getBaseUrl()
    {
        if (is_string($this->baseUrl)) {
            return $this->baseUrl;
        }
        throw new \GitlabCI\Exception\LogicException('Base url expects a string, "' . gettype($this->baseUrl) . '" defined');
    }

    /**
     * @param string $sBaseUrl
     * @return \GitlabCI\HttpClient\HttpClient
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setBaseUrl($sBaseUrl)
    {
        if (is_string($sBaseUrl)) {
            $this->baseUrl = $sBaseUrl;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Base url expects a string, "' . gettype($sBaseUrl) . '" given');
    }

    /**
     * @return \GitlabCI\HttpClient\HttpClientInterface
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getHttpClient()
    {
        if ($this->httpClient instanceof \GitlabCI\HttpClient\HttpClientInterface) {
            return $this->httpClient;
        }
        throw new \GitlabCI\Exception\LogicException('Http client expects an instance of "\GitlabCI\HttpClient\HttpClientInterface", "' . (is_object($this->httpClient) ? get_class($this->httpClient) : gettype($this->httpClient)) . '" defined');
    }

    /**
     * @param \GitlabCI\HttpClient\HttpClientInterface $oHttpClient
     * @return \GitlabCI\Client
     */
    public function setHttpClient(\GitlabCI\HttpClient\HttpClientInterface $oHttpClient)
    {
        $this->httpClient = $oHttpClient;
        return $this;
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
     * @return \GitlabCI\Client
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setOptions(array $aOptions)
    {
        if (is_array($aOptions)) {
            $this->options = $aOptions;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Options url expects an array, "' . gettype($aOptions) . '" given');
    }

    /**
     * @param scalar $sName
     * @param mixed $sValue
     * @return \GitlabCI\Client
     * @throws \GitlabCI\Exception\LogicException
     */
    public function setOption($sName, $sValue)
    {
        if (is_scalar($sName)) {
            $this->options[$sName] = $sValue;
            return $this;
        }
        throw new \GitlabCI\Exception\LogicException('Name expects a scalar value, "' . gettype($sName) . '" given');
    }

    /**
     * @return array
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getHeaders()
    {
        return $this->getHttpClient()->getHeaders();
    }

    /**
     * @param array $aHeaders
     * @return \GitlabCI\Client
     */
    public function setHeaders(array $aHeaders)
    {
        $this->getHttpClient()->setHeaders($aHeaders);
        return $this;
    }

    /**
     * Clears used headers
     * @return \GitlabCI\Client
     */
    public function clearHeaders()
    {
        $this->getHttpClient()->clearHeaders();
        return $this;
    }
}
