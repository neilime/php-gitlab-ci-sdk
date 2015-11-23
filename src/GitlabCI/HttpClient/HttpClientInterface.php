<?php

namespace GitlabCI\HttpClient;

interface HttpClientInterface
{

    /**
     * Send a GET request
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param array $aHeaders : reconfigure the request headers for this call only
     * @return array : data
     */
    public function get($sPath, array $aParameters = array(), array $aHeaders = array());

    /**
     * Send a POST request
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param array $aHeaders : reconfigure the request headers for this call only
     * @return array : data
     */
    public function post($sPath, array $aParameters = array(), array $aHeaders = array());

    /**
     * Send a PATCH request
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param array $aHeaders : reconfigure the request headers for this call only
     * @return array : data
     */
    public function patch($sPath, array $aParameters = array(), array $aHeaders = array());

    /**
     * Send a PUT request
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param array $aHeaders : reconfigure the request headers for this call only
     * @return array : data
     */
    public function put($sPath, array $aParameters = array(), array $aHeaders = array());

    /**
     * Send a DELETE request
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param array $aHeaders : reconfigure the request headers for this call only
     * @return array : data
     */
    public function delete($sPath, array $aParameters = array(), array $aHeaders = array());

    /**
     * Send a request to the server, receive a response, decode the response and returns an associative array
     * @param string $sPath : request path
     * @param array $aParameters : GET Parameters
     * @param string $sHttpMethod : HTTP method to use
     * @param array  $aHeaders : request headers
     * @return array : data
     */
    public function request($sPath, array $aParameters = array(), $sHttpMethod = 'GET', array $aHeaders = array());

    /**
     * Change an option value.
     * @param string $sName : the option name
     * @param mixed $sValue : the value
     *
     */
    public function setOption($sName, $sValue);

    /**
     * Set HTTP headers
     * @param array $aHeaders
     */
    public function setHeaders(array $aHeaders);
}
