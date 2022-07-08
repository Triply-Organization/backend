<?php

namespace App\Tests\Unit\Mapper;

use App\Entity\Tour;
use App\Entity\User;
use App\Mapper\TourCreateMapper;
use App\Request\TourRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class TourCreateMapperTest extends TestCase
{
    public function testMapping()
    {
        $user = new User();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $securityMock->expects($this->once())->method('getUser')->willReturn($user);
        $tourRequest = new TourRequest();
        $tourRequest->setTitle('Tour Can Tho');
        $tourRequest->setDuration(3);
        $tourRequest->setMaxPeople(10);
        $tourRequest->setMinAge(20);
        $tourRequest->setOverView('Good tour');
        $tourCreateMapper = new TourCreateMapper($securityMock);
        $result = $tourCreateMapper->mapping($tourRequest);

        $this->assertEquals(null, $result->getId());
        $this->assertEquals('Tour Can Tho', $result->getTitle());
        $this->assertEquals(3, $result->getDuration());
        $this->assertEquals(10, $result->getMaxPeople());
        $this->assertEquals(20, $result->getMinAge());
        $this->assertEquals('Good tour', $result->getOverView());
        $this->assertEquals($user, $result->getCreatedUser());
    }

}
