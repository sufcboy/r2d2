<?php

namespace R2D2\Utils;

use GuzzleHttp\Client;
use R2D2\Utils\ApiException;

class Api
{
    /**
     * @todo Make this a config
     * @var string
     */
    private $endpoint = 'https://death.star.api/​';
    // private $endpoint = 'https://jsonplaceholder.typicode.com';

    /**
     * @todo Make this a config
     * @var string
     */
    private $certificatePath = '/data/certs/server.pem';

    /**
     * @todo Make this a config
     * @var string
     */
    private $certificatePassword = 'DarthRulesOk';

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Makes GET requests
     *
     * @param string $path
     * @param string $token
     * @return string
     */
    public function makeGetRequest($path, $token = null)
    {
        // Common across all API calls
        $options = $this->getBaseOptions();

        // Add headers
        if (null !== $token && true === is_string($token)) {
            $options['headers'] = $this->getTokenHeader();
        }

        return $this->makeRequest('GET', $path, $options);
    }

    /**
     * Makes POST requests
     *
     * @param string $path
     * @param array $formParams
     * @return string
     */
    public function makePostRequest($path, array $formParams = null)
    {
        // Common across all API calls
        $options = $this->getBaseOptions();

        if (null !== $formParams) {
            $options['form_params'] = $formParams;
        }

        return $this->makeRequest('POST', $path, $options);
    }

    /**
     * Call the delete method on
     *
     * @param string $path
     * @param string $token
     * @param array $headersExtra
     * @return string
     */
    public function makeDeleteRequest($path, $token, $headersExtra = [])
    {
        // Common across all API calls
        $options = $this->getBaseOptions();

        // Define headers
        $headers = $this->getTokenHeader($token);
        $headers = array_merge($headers, $headersExtra);

        $options['headers'] = $headers;

        return $this->makeRequest('DELETE', $path, $options);
    }

    /**
     * Returns the header when token is required
     *
     * @param string $token
     * @return array
     */
    private function getTokenHeader($token)
    {
        return [
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Calls the method on the client for path
     *
     * @param string $method
     * @param string $path
     * @param array $options
     * @return string
     */
    private function makeRequest($method, $path, array $options)
    {
        // Check params
        if (false === is_string($path) || 0 === strlen($path)) {
            throw new ApiException(
                'Invalid path provided',
                ApiException::ERROR_CODE_INVALID_PATH
            );
        }

        // Attempt to make the call
        try {
            $response = $this->getClient()->request(
                $method,
                $path,
                $options
            );

            $code = $response->getStatusCode();

            // @todo add other 200 codes to array
            if (200 !== $code) {
                throw new ApiException(
                    'Invalid response received from service: ' . $code,
                    ApiException::ERROR_CODE_INVALID_RESPONSE_CODE
                );
            }

            return (string)$response->getBody();

        // Connection issues
        } catch (\GuzzleHttp\Exception\ConnectException $ex) {
            throw new ApiException(
                'Connection error',
                ApiException::ERROR_CODE_CONNECTION_ERROR
            );

        // Any other issues
        } catch (Exception $ex) {
            throw new ApiException(
                $ex->getMessage(),
                ApiException::ERROR_CODE_GENERIC
            );
        }
    }

    /**
     * Guzzle options for all of the requests
     *
     * @return array
     */
    private function getBaseOptions()
    {
        return [
            'cert' => [
                $this->certificatePath,
                $this->certificatePassword
            ]
        ];
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client([
                'base_uri' => $this->endpoint,
                'timeout' => 2.0
            ]);
        }

        return $this->client;
    }
}