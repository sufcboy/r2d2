<?php

namespace R2D2\Prisons;

use R2D2\Hack\Mainframe;
use R2D2\Utils\Api;
use R2D2\Prisons\PrisonerException;

class Prisoner
{
    /**
     * API path
     *
     * @var string
     */
    private $prisonerPath = 'prisoner';

    /**
     * Return details from API regarding prisoners
     *
     * @param string $prisonerName
     * @return array
     */
    public function getPrisonerDetails($prisonerName = 'leia')
    {
        if (false === is_string($prisonerName) || 0 === strlen($prisonerName)) {
            throw new PrisonerException(
                'Prisoner name needs to be passed as a string',
                PrisonerException::ERROR_CODE_INVALID_PRISONER
            );
        }

        // Hack the mainframe for token key
        $mainframe = new Mainframe();
        $token = $mainframe->getAccessToken();

        // Now use the token to get the details
        $api = new Api();

        // @todo Add some checks around this
        return json_decode($api->makeGetRequest(
            $this->prisonerPath . '/' . $prisonerName,
            $token
        ));
    }
}