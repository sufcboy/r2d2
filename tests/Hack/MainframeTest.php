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
        $this->mainframe->getAccessToken();
    }
}