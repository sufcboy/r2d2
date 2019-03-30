<?php

declare(strict_types=1);

namespace R2D2\Hack;

use R2D2\Utils\Api;
use R2D2\Hack\MainframeException;

class Mainframe
{
    const ACCESS_TOKEN_KEY = 'access_token';

    /**
     * @todo Move to config
     * @var string
     */
    private $clientSecret = 'Alderan';

    /**
     * @todo Move to config
     * @var string
     */
    private $clientId = 'R2D2';

    /**
     * @var string
     */
    private $tokenPath = 'token';

    /**
     * @var R2D2\Utils\Api
     */
    private $api;

    /**
     * Returns the access token from mainframe
     * @throws MainframeException
     * @return string
     */
    public function getAccessToken(): string
    {
        $rawResponse = $this->getApi()->makePostRequest(
            $this->tokenPath,
            [
                'ClientSecret' => $this->clientSecret,
                'ClientID' => $this->clientId
            ]
        );

        $tokenData = json_decode($rawResponse, true);

        if (false === is_array($tokenData) || false === array_key_exists(self::ACCESS_TOKEN_KEY, $tokenData)) {
            throw new MainframeException(
                'Invalid token data received from API',
                MainframeException::ERROR_CODE_INVALID_TOKEN
            );
        }

        return $tokenData[self::ACCESS_TOKEN_KEY];
    }

    /**
     * Returns the path for token
     *
     * @return string
     */
    public function getTokenPath(): string
    {
        return $this->tokenPath;
    }

    /**
     * Sets the API
     *
     * @param Api $api
     * @return void
     */
    public function setApi(Api $api): void
    {
        $this->api = $api;
    }

    /**
     * Get the API
     *
     * @return Api
     */
    public function getApi(): Api
    {
        if (null === $this->api) {
            $this->api = new Api();
        }

        return $this->api;
    }
}
