<?php

declare(strict_types=1);

namespace R2D2\Deathstar\Reactors;

use PHPUnit\Framework\TestCase;
use R2D2\Deathstar\Reactors\Exhaust;

class ExhaustTest extends TestCase
{
    private $exhaust;

    public function setUp(): void
    {
        $this->exhaust = new Exhaust();
    }

    public function testDelectExhaust(): void
    {
        $exhaustId = 2;
        $mockToken = '2111313123213';
        $expectedResponse = ['deleted' => true];

        // Mock the bearer token
        $mockMainframe = $this->getMockBuilder('R2D2\Hack\Mainframe')
            ->disableOriginalConstructor()
            ->getMock();

        $mockMainframe->expects($this->once())
            ->method('getAccessToken')
            ->willReturn($mockToken);

        $this->exhaust->setMainframe($mockMainframe);

        // Mock the API
        $mockApi = $this->getMockBuilder('R2D2\Utils\Api')
            ->disableOriginalConstructor()
            ->getMock();

        $mockApi->expects($this->once())
            ->method('makeDeleteRequest')
            ->with(
                $this->exhaust->getExhaustPath() . '/' . $exhaustId,
                $mockToken
            )
            ->willReturn(json_encode($expectedResponse));

        $this->exhaust->setApi($mockApi);

        // Assert equals
        $this->assertEquals(
            $expectedResponse,
            $this->exhaust->deleteExhaust($exhaustId)
        );
    }
}
