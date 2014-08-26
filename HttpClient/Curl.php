<?php

namespace Netgen\HtmlPdfApi\HttpClient;

use Netgen\HtmlPdfApi\HttpClientInterface;


class Curl implements HttpClientInterface {

    /**
     * Base url of the api
     *
     * @var string
     */
    protected $host;

    /**
     * Security token
     *
     * @var string
     */
    protected $token;

    /**
     * Constructor
     *
     * @param string $host  Base url of the api
     * @param string $token Security token
     */
    public function __construct($host, $token)
    {
        $this->host = $host;
        $this->token = $token;
    }

    /**
     * Initiates curl session for POST method
     *
     * @param string $url   Relative url for the request
     * @param array $params Parameters for the request
     * @return resource
     */
    private function initiatePost($url, $params)
    {
        $ch = curl_init($this->host.'/'.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authentication: Token ". $this->token));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        return $ch;
    }

    /**
     * Initiates curl session for GET method
     *
     * @param string $url   Relative url for the request
     * @param array $params Parameters for the request
     * @return resource
     */
    private function initiateGet($url, $params)
    {;
        if (!empty($params))
        {
            $parameter=implode(array_values($params));
            $url .= '/'.$parameter;
        }
        $ch = curl_init($this->host.'/'.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authentication: Token ". $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return $ch;
    }

    /**
     * Initiates curl session for DELETE method
     *
     * @param string $url   Relative url for the request
     * @param array $params Parameters for the request
     * @return resource
     */
    private function initiateDelete($url, $params)
    {

        if (!empty($params))
        {
            $parameter=implode(array_values($params));
            $url .= '/'.$parameter;
        }
        $ch = curl_init($this->host.'/'.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authentication: Token ". $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $ch;
    }

    /**
     * Initiate curl session for POST upload
     *
     * @param string $url   Relative url for the request
     * @param array $params Parameters for the request
     * @return resource
     */
    private function initiateUpload($url, $params)
    {
        $ch = curl_init($this->host.'/'.$url);

        if (!empty($params['file']))
        {
            $params['file'] .= ";filename=".basename($params['file']);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authentication: Token ". $this->token
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        return $ch;
    }

    /**
     * Sends the request via curl
     *
     * @param string $url       Relative url for the request
     * @param array $params     Parameters for the request
     * @param string $method    Request method
     * @return string
     */
    public function sendRequest($url, $params, $method)
    {
        if($method=='POST')
        {
            if (!empty($params['file']))
                $ch = $this->initiateUpload($url, $params);
            else
                $ch = $this->initiatePost($url, $params);
        }
        else if($method=='GET')
        {
            $ch = $this->initiateGet($url, $params);
        }
        else if($method=='DELETE')
        {
            $ch = $this->initiateDelete($url, $params);
        }
        else{
            throw new \Exception("Method ".$method." not supported!");
        }

        if (! $ret = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }

        curl_close($ch);
        return $ret;
    }

} 