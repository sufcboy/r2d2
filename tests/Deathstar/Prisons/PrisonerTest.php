<?php

namespace R2D2\Deathstar\Prisons;

use PHPUnit\Framework\TestCase;
use R2D2\Deathstar\Prisons\Prisoner;

class PrisonerTest extends TestCase
{
    private $prisoner;

    public function setUp()
    {
        $this->prisoner = new Prisoner();
    }

    /**
     * @dataProvider getTestFindLeiaLocation
     * @param array $testResponse
     * @param array $expected
     */
    public function testFindLeiaLocation($testResponse, $expected)
    {
        $prisoner = 'leia';
        $mockToken = 'MYTOKEN';

        // Mock the bearer token
        $mockMainframe = $this->getMockBuilder('R2D2\Hack\Mainframe')
            ->disableOriginalConstructor()
            ->getMock();

        $mockMainframe->expects($this->once())
            ->method('getAccessToken')
            ->willReturn($mockToken);

        $this->prisoner->setMainframe($mockMainframe);

        // Mock the API
        $mockApi = $this->getMockBuilder('R2D2\Utils\Api')
            ->disableOriginalConstructor()
            ->getMock();

        $mockApi->expects($this->once())
            ->method('makeGetRequest')
            ->with(
                $this->prisoner->getPrisonerPath() . '/' . $prisoner,
                $mockToken
            )
            ->willReturn($testResponse);

        $this->prisoner->setApi($mockApi);
        $result = $this->prisoner->findPrisonerLocation($prisoner);

        // Make the call
        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getTestFindLeiaLocation()
    {
        return [
            [
                '{
                    "cell": "01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111",
                    "block": "01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"
                }',
                [
                    'cell' => 'Cell 20',
                    'block' => 'Detenth'
                ]
            ]
        ];
    }
}
