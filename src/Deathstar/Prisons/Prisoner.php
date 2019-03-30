<?php

declare(strict_types=1);

namespace R2D2\Deathstar\Prisons;

use R2D2\Hack\Mainframe;
use R2D2\Utils\Api;
use R2D2\Deathstar\Prisons\PrisonerException;

class Prisoner
{
    /**
     * API path
     *
     * @var string
     */
    private $prisonerPath = 'prisoner';

    /**
     * @var R2D2\Hack\Mainframe
     */
    private $mainframe;

    /**
     * @var R2D2\Utils\Api
     */
    private $api;

    /**
     * Return details from API regarding prisoners
     *
     * @param string $prisonerName
     * @return array
     */
    public function getPrisonerDetails(string $prisonerName = 'leia'): Array
    {
        if (0 === strlen($prisonerName)) {
            throw new PrisonerException(
                'Prisoner name needs to be passed as a string',
                PrisonerException::ERROR_CODE_INVALID_PRISONER
            );
        }

        // @todo Add some checks around this
        return json_decode($this->getApi()->makeGetRequest(
            $this->prisonerPath . '/' . $prisonerName,
            $this->getMainframe()->getAccessToken()
        ), true);
    }

    /**
     * Returns the prisoner path
     *
     * @return string
     */
    public function getPrisonerPath(): string
    {
        return $this->prisonerPath;
    }

    /**
     * Finds the prisoner details and translates
     *
     * @param string $prisoner
     * @return array
     */
    public function findPrisonerLocation(string $prisoner): Array
    {
        $prisonerDetails = $this->getPrisonerDetails($prisoner);

        foreach ($prisonerDetails as $key => $binary) {
            $prisonerDetails[$key] = $this->translate($binary);
        }

        return $prisonerDetails;
    }

    /**
     * Translate the binary
     *
     * @param string $binary
     * @return string
     */
    private function translate(string $binary): string
    {
        $unpacked = unpack('A*', pack('H*', base_convert($binary, 2, 16)));

        return $unpacked[array_keys($unpacked)[0]];
    }

    /**
     * Sets the mainframe
     *
     * @param Mainframe $mainframe
     * @return void
     */
    public function setMainframe(Mainframe $mainframe): void
    {
        $this->mainframe = $mainframe;
    }

    /**
     * Get the mainframe
     *
     * @return Mainframe
     */
    public function getMainframe(): Mainframe
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
