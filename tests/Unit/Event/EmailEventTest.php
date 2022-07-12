<?php

namespace App\Tests\Unit\Event;

use App\Event\EmailEvent;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

class EmailEventTest extends TestCase
{
    public function testGetMail()
    {
        $phpMailMock = $this->getMockBuilder(PHPMailer::class)->disableOriginalConstructor()->getMock();
        $emailEvent = new EmailEvent($phpMailMock);

        $result = $emailEvent->getEmail();
        $this->assertEquals($result, $phpMailMock);
    }
}
