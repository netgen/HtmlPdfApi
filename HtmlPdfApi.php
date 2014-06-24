<?php

namespace Netgen\HtmlPdfApi;

class HtmlPdfApi {

    /**
     * Client for sending requests
     *
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * Validator of parameters
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param HttpClientInterface $http_client
     * @param ValidatorInterface $validator
     */
    public function __construct(HttpClientInterface $http_client, ValidatorInterface $validator)
    {
        $this->client = $http_client;
        $this->validator = $validator;
    }

    /**
     * Generates PDF file from provided (relative) URL
     *
     * @param array $params Parameters for pdf generating (parameter 'url' must be set)
     * @return response
     * @throws \Exception
     */
    public function generateFromURL($params)
    {
        if(empty($params['url']))
            throw new \Exception('Parameter \'url\' must be set' );

        $params = $this->validator->validate($params);

        try {
            return $this->client->sendRequest('pdf', $params, 'POST');
        } catch (\Exception $ex) {
            throw $ex;
        }

    }

    /**
     * Generates PDF file from provided HTML string
     *
     * @param array $params Parameters for pdf generating (parameter 'html' must be set)
     * @return response
     * @throws \Exception
     */
    public function generateFromHTML($params)
    {
        if(empty($params['html']))
            throw new \Exception('Parameter \'html\' must be set' );

        $params = $this->validator->validate($params);

        try {
            return $this->client->sendRequest('pdf', $params, 'POST');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Generates PDF file from provided file (html, zip, tar.gz...)
     *
     * @param array $params Parameters for pdf generating (parameter 'file' must be set)
     * @return response
     * @throws \Exception
     */
    public function generateFromFile($params)
    {
        if(empty($params['file']))
            throw new \Exception('Parameter \'file\' must be set' );

        $params['file'] = '@'.$params['file'];
        $params = $this->validator->validate($params);
        $this->validator->validateHtmlFile($params['file']);

        try {
            return $this->client->sendRequest('pdf', $params, 'POST');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Gets number of available credits
     *
     * @return response
     * @throws \Exception
     */
    public function getCredits()
    {
        $params = array();

        try {
            return $this->client->sendRequest('credits', $params, 'GET');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Uploads new asset to HTMLPDFAPI server
     *
     * @param $filePath     Path to the file to upload
     * @return response
     * @throws \Exception
     */
    public function uploadAsset($filePath)
    {
        $params = array( 'file' => '@'.$filePath );
        $params = $this->validator->validate($params);
        $this->validator->validateAssetFile($params['file']);

        try {
            return $this->client->sendRequest('assets', $params, 'POST');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Downloads asset from server by id
     *
     * @param string $id    ID of the asset
     * @return response
     * @throws \Exception
     */
    public function getAsset($id)
    {
        $params = array( 'id' => $id );
        $params = $this->validator->validate($params);

        try {
            return $this->client->sendRequest('assets', $params, 'GET');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Deletes asset by ID
     *
     * @param string $id    ID of the asset
     * @return response
     * @throws \Exception
     */
    public function deleteAsset($id)
    {
        $params = array( 'id' => $id );
        $params = $this->validator->validate($params);

        try {
            return $this->client->sendRequest('assets', $params, 'DELETE');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Gets the list of available assets
     *
     * @return response
     * @throws \Exception
     */
    public function getAssetList()
    {
        $params = array();

        try {
            return $this->client->sendRequest('assets', $params, 'GET');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Gets the asset ID by the name
     *
     * @param string $name  Name of the asset
     * @return string $id   ID of the asset
     * @throws \Exception
     */
    public function getAssetID($name)
    {
        $list = $this->getAssetList();

        $list_json = json_decode($list, true);

        foreach ($list_json as $asset)
        {
            if ($asset['name'] == $name)
            {
                return $asset['id'];
            }
        }
        throw new \Exception('No asset found by name');
    }
} 