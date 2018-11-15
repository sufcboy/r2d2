<?php

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
     * Returns the access token from mainframe
     * @throws MainframeException
     * @return string
     */
    public function getAccessToken()
    {
        $api = new Api();
        $rawResponse = $api->makePostRequest(
            'token',
            [
                'ClientSecret' => $this->clientSecret,
                'ClientID' => $this->clientId
            ]
        );

        $tokenData = json_decode($rawResponse);

        if (false === is_array($tokenData) || false === array_key_exists(self::ACCESS_TOKEN_KEY, $tokenData)) {
            throw new MainframeException(
                'Invalid token data received from API',
                MainframeException::ERROR_CODE_INVALID_TOKEN
            );
        }

        return $tokenData[self::ACCESS_TOKEN_KEY];
    }
}
