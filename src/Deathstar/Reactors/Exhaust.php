<?php

namespace R2D2\Deathstar\Reactors;

use R2D2\Utils\Api;
use R2D2\Deathstar\Reactors\ReactorException;

class Exhaust
{
    /**
     * Path to exhaust endpoint
     *
     * @var string
     */
    private $exhaustPath = 'reactor/exhaust';

    /**
     * Delect exhaust id
     *
     * @param integer $exhaustId
     * @return string
     */
    public function deleteExhaust($exhaustId = 1)
    {
        // Check id
        if (false === is_numeric($exhaustId)) {
            throw new ReactorException(
                'Invalid exhaust id provided',
                ReactorException::ERROR_CODE_INVALID_EXHAUST_ID
            );
        }

        // Get token
        $mainframe = new Mainframe();
        $token = $mainframe->getAccessToken();

        // Now use the token to get the details
        $api = new Api();

        // @todo Add some checks around this
        return json_decode($api->makeDeleteRequest(
            $this->exhaustPath . '/' . $exhaustId,
            $token,
            ['x-torpedoes' => 2]
        ));
    }
}