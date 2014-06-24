<?php

namespace Netgen\HtmlPdfApi\HttpClient;

use Netgen\HtmlPdfApi\HttpClientInterface;
use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Component\Finder\Finder;

class Guzzle extends Client implements HttpClientInterface {

    /**
     * Constructor
     *
     * @param string $host          Base url of the api
     * @param string $token         Security token
     * @param string $json_location Path to service.json file
     * @param string $root          Root kernel directory
     */
    public function __construct($host, $token, $json_location, $root)
    {
        //fallback value
        $default = array(
            //'hostname' => '{host}',
            //'host' => 'https://htmlpdfapi.com//api//v1//'
        );
        $config = array(
            'host' => $host,
            'token' => $token
        );
        $required = array('host', 'token');
        $config = Collection::fromConfig($config, $default, $required);

        parent::__construct($config['host']);

        $finder = new Finder();
        $finder->files()->name('service.json')->in($root.$json_location);
        if ($finder->count() != 1)
            throw new \Exception("Problem with locating service.json file.");
        foreach ($finder as $file)
        {
            if ($file->getRelativePathname()=="service.json")
                $json = $file->getRealPath();
        }
        $this->setDescription(ServiceDescription::factory($json));

        $header = array( 'Authentication' => 'Token' . " " . $config->get('token') );
        $this->setDefaultOption("headers", $header);
    }

    /**
     * Sends the request via Guzzle Client
     *
     * @param string $url       Relative url for the request
     * @param array $params     Parameters for the request
     * @param string $method    Method for the request
     * @return array|\Guzzle\Http\Message\Response|\Netgen\HtmlPdfApiBundle\Component\response
     * @throws \Exception
     */
    public function sendRequest($url, $params, $method)
    {
        if (!empty($params['html'])){
            $commandName = 'GenerateFromHTML';
        }
        else if (!empty($params['pdf'])){
            $commandName = 'GenerateFromPDF';
        }
        else if (!empty($params['file'])&& $url=='pdf'){
            $commandName = 'GenerateFromFile';
        }
        else {
            $operations = $this->getDescription()->getOperations();
            foreach ($operations as $operation)
            {
                if ($operation->getUri()==$url && $operation->getHttpMethod() == $method)
                {
                    $commandName = $operation->getName();
                }
            }
        }
        try{
            $command = $this->getCommand($commandName, $params);
            $ret = $this->execute($command);
            return $ret;
        }catch(ClientErrorResponseException $exception){
            throw new \Exception($exception->getResponse()->getBody(true), $exception->getResponse()->getStatusCode());
        }
    }
} 