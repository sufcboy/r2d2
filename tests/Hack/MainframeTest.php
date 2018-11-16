<?php

namespace R2D2\Hack;

use R2D2\Hack\Mainframe;
use PHPUnit\Framework\TestCase;

class MainframeTest extends TestCase
{
    private $mainframe;

    public function setUp()
    {
        $this->mainframe = new Mainframe();
    }

    public function testGetAccessToken()
    {
        $expectedToken = 'MY_TOKEN';

        // Mock the API
        $mockApi = $this->getMockBuilder('R2D2\Utils\Api')
            ->disableOriginalConstructor()
            ->getMock();

        $mockApi->expects($this->once())
            ->method('makePostRequest')
            ->with(
                $this->mainframe->getTokenPath(),
                [
                    // @todo Get the values in a cleaner way
                    'ClientSecret' => 'Alderan',
                    'ClientID' => 'R2D2'
                ]
            )
            ->willReturn(json_encode([
                "access_token" => $expectedToken,
                "expires_in" => 99999999999,
                "token_type" => "Bearer",
                "scope" => "TheForce"
            ]));

        $this->mainframe->setApi($mockApi);

        // Assert
        $this->assertEquals(
            $expectedToken,
            $this->mainframe->getAccessToken()
        );
    }
}