<?php

namespace Netgen\HtmlPdfApi;


interface HttpClientInterface {

    /**
     * Sends request with provided parameters
     *
     * @param string $url       Relative URL for the request
     * @param array $params     Parameters
     * @param string $method    Method of the request
     *
     * @return  string          Response
     */
    public function sendRequest($url, $params, $method);
} 