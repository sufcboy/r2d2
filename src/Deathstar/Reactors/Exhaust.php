<?php

namespace R2D2\Deathstar\Reactors;

use R2D2\Hack\Mainframe;
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
     * @var R2D2\Hack\Mainframe
     */
    private $mainframe;

    /**
     * @var R2D2\Utils\Api
     */
    private $api;

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

        // @todo Add some checks around this
        return json_decode($this->getApi()->makeDeleteRequest(
            $this->exhaustPath . '/' . $exhaustId,
            $this->getMainframe()->getAccessToken(),
            ['x-torpedoes' => 2]
        ), true);
    }

    /**
     * Returns API path
     *
     * @return string
     */
    public function getExhaustPath()
    {
        return $this->exhaustPath;
    }

    /**
     * Sets the mainframe
     *
     * @param Mainframe $mainframe
     * @return void
     */
    public function setMainframe(Mainframe $mainframe)
    {
        $this->mainframe = $mainframe;
    }

    /**
     * Get the mainframe
     *
     * @return Mainframe
     */
    public function getMainframe()
    {
        if (null === $this->mainframe) {
            $this->mainframe = new Mainframe();
        }

        return $this->mainframe;
    }

    /**
     * Sets the API
     *
     * @param Api $api
     * @return void
     */
    public function setApi(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Get the API
     *
     * @return Api
     */
    public function getApi()
    {
        if (null === $this->api) {
            $this->api = new Api();
        }

        return $this->api;
    }
}