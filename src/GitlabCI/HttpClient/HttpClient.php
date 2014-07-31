<?php

namespace GitlabCI\HttpClient;

class HttpClient implements \GitlabCI\HttpClient\HttpClientInterface
{

    /**
     * @var array
     */
    protected $options = array(
        'user_agent' => 'php-gitlab-ci-api (http://github.com/neilime/php-gitlab-ci-api)',
        'timeout' => 10,
    );

    /**
     * @var string
     */
    protected $baseUrl = null;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @var \GitlabCI\HttpClient\Message\Response
     */
    protected $lastResponse;

    /**
     * @var \GitlabCI\HttpClient\Message\Request
     */
    protected $lastRequest;

    /**
     * Constructor
     * @param string $sBaseUrl
     * @param array $aOptions
     * @param \Buzz\Client\ClientInterface $oClient
     */
    public function __construct($sBaseUrl = null, array $aOptions = array(), \Buzz\Client\ClientInterface $oClient = null)
    {
        if ($sBaseUrl) {
            $this->setBaseUrl($sBaseUrl);
        }

        if ($aOptions) {
            $this->setOptions(array_merge($this->getOptions(), $aOptions));
        }

        if ($oClient) {
            $this->setClient($oClient);
        }

        $this->addListener(new \GitlabCI\HttpClient\Listener\ErrorListener($this->getOptions()))->clearHeaders();
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function get($sPath, array $aParameters = array(), array $aHeaders = array())
    {
        if (is_string($sPath)) {
            if ($aParameters) {
                $sPath .= (false === strpos($sPath, '?') ? '?' : '&') . http_build_query($aParameters, '', '&');
            }
            return $this->request($sPath, array(), 'GET', $aHeaders);
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Path expects a string, "' . gettype($sPath) . '" given');
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     */
    public function post($sPath, array $aParameters = array(), array $aHeaders = array())
    {
        return $this->request($sPath, $aParameters, 'POST', $aHeaders);
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     */
    public function patch($sPath, array $aParameters = array(), array $aHeaders = array())
    {
        return $this->request($sPath, $aParameters, 'PATCH', $aHeaders);
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     */
    public function delete($sPath, array $aParameters = array(), array $aHeaders = array())
    {
        return $this->request($sPath, $aParameters, 'DELETE', $aHeaders);
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     */
    public function put($sPath, array $aParameters = array(), array $aHeaders = array())
    {
        return $this->request($sPath, $aParameters, 'PUT', $aHeaders);
    }

    /**
     * @param string $sPath
     * @param array $aParameters
     * @param setL $sHttpMethod
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\Message\Response
     * @throws \GitlabCI\Exception\ErrorException
     * @throws \GitlabCI\Exception\RuntimeException
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function request($sPath, array $aParameters = array(), $sHttpMethod = 'GET', array $aHeaders = array())
    {
        if (is_string($sPath)) {
            $sPath = trim($this->getBaseUrl() . $sPath, '/');

            $oRequest = $this->createRequest($sHttpMethod, $sPath);
            $oRequest->addHeaders($aHeaders);
            $oRequest->setContent(http_build_query($aParameters));

            // Process listeners
            $aListeners = $this->getListeners();
            if ($aListeners) {
                foreach ($aListeners as $oListener) {
                    $oListener->preSend($oRequest);
                }
            }

            $oResponse = new \GitlabCI\HttpClient\Message\Response();

            try {
                $this->getClient()->send($oRequest, $oResponse);
            } catch (\LogicException $oException) {
                throw new \GitlabCI\Exception\ErrorException($oException->getMessage());
            } catch (\RuntimeException $oException) {
                throw new \GitlabCI\Exception\RuntimeException($oException->getMessage());
            }

            $this->setLastRequest($oRequest);
            $this->setLastResponse($oResponse);

            if ($aListeners) {
                foreach ($aListeners as $oListener) {
                    $oListener->postSend($oRequest, $oResponse);
                }
            }

            return $oResponse;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Path expects a string, "' . gettype($sPath) . '" given');
    }

    /**
     * @param string $sHttpMethod
     * @param string $sUrl
     * @return \GitlabCI\HttpClient\Message\Request
     */
    protected function createRequest($sHttpMethod, $sUrl)
    {
        $oRequest = new \GitlabCI\HttpClient\Message\Request($sHttpMethod);
        $oRequest->setHeaders($this->headers);
        $oRequest->fromUrl($sUrl);

        return $oRequest;
    }

    /**
     * @param \Buzz\Listener\ListenerInterface $oListener
     * @return \GitlabCI\HttpClient\HttpClient
     */
    public function addListener(\Buzz\Listener\ListenerInterface $oListener)
    {
        $this->listeners[get_class($oListener)] = $oListener;
        return $this;
    }

    /**
     * @return array
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getListeners()
    {
        if (is_array($this->listeners)) {
            return $this->listeners;
        }
        throw new \GitlabCI\Exception\LogicException('Listeners expects an array, "' . gettype($this->listeners) . '" defined');
    }

    /**
     * @param array $aListeners
     * @return \GitlabCI\HttpClient\HttpClient
     * @throws \GitlabCI\Exception\InvalidArgumentException
     */
    public function setListeners(array $aListeners)
    {
        if (is_array($aListeners)) {
            $this->listeners = $aListeners;
            return $this;
        }
        throw new \GitlabCI\Exception\InvalidArgumentException('Listeners url expects an array, "' . gettype($aListeners) . '" given');
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
     * @return \GitlabCI\HttpClient\Message\Request
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getLastRequest()
    {
        if ($this->lastRequest instanceof \GitlabCI\HttpClient\Message\Request) {
            return $this->lastRequest;
        }
        throw new \GitlabCI\Exception\LogicException('Last request expects an instance of "\GitlabCI\HttpClient\Message\Request", "' . (is_object($this->lastRequest) ? get_class($this->lastRequest) : gettype($this->lastRequest)) . '" defined');
    }

    /**
     * @param \GitlabCI\HttpClient\Message\Request $oRequest
     * @return \GitlabCI\HttpClient\HttpClient
     */
    public function setLastRequest(\GitlabCI\HttpClient\Message\Request $oRequest)
    {
        $this->lastRequest = $oRequest;
        return $this;
    }

    /**
     * @return \GitlabCI\HttpClient\Message\Response
     * @throws \GitlabCI\Exception\LogicException
     */
    public function getLastResponse()
    {
        if ($this->lastResponse instanceof \GitlabCI\HttpClient\Message\Response) {
            return $this->lastResponse;
        }
        throw new \GitlabCI\Exception\LogicException('Last response expects an instance of "\GitlabCI\HttpClient\Message\Response", "' . (is_object($this->lastResponse) ? get_class($this->lastResponse) : gettype($this->lastResponse)) . '" defined');
    }

    /**
     * @param \GitlabCI\HttpClient\Message\Response $oResponse
     * @return \GitlabCI\HttpClient\HttpClient
     */
    public function setLastResponse(\GitlabCI\HttpClient\Message\Response $oResponse)
    {
        $this->lastResponse = $oResponse;
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
     * @return \GitlabCI\HttpClient\HttpClient
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
     * @return \GitlabCI\HttpClient\HttpClient
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
        if (is_array($this->headers)) {
            return $this->headers;
        }
        throw new \GitlabCI\Exception\LogicException('Headers expects an array, "' . gettype($this->headers) . '" defined');
    }

    /**
     * @param array $aHeaders
     * @return \GitlabCI\HttpClient\HttpClient
     */
    public function setHeaders(array $aHeaders)
    {
        $this->headers = array_merge($this->getHeaders(), $aHeaders);
        return $this;
    }

    /**
     * Clears used headers
     */
    public function clearHeaders()
    {
        $this->headers = array();
        return $this;
    }

}
