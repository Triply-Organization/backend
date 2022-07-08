<?php

namespace App\Tests\Unit\Mapper;

use App\Entity\Tour;
use App\Entity\User;
use App\Mapper\TourUpdateMapper;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class TourUpdateMapperTest extends TestCase
{
    public function testMapping()
    {
        $user = new User();
        $tour = new Tour();
        $tour->setOverView('bad');
        $tour->setMinAge(50);
        $tour->setDuration(2);
        $tour->setTitle('Tour da lat');
        $tour->setMaxPeople(50);
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $securityMock->expects($this->once())->method('getUser')->willReturn($user);
        $tourUpdateRequest = new TourUpdateRequest();
        $tourUpdateRequest->setTitle('Tour Can Tho');
        $tourUpdateRequest->setDuration(3);
        $tourUpdateRequest->setMaxPeople(10);
        $tourUpdateRequest->setMinAge(20);
        $tourUpdateRequest->setOverView('Good tour');
        $tourUpdateMapper = new TourUpdateMapper($securityMock);
        $result = $tourUpdateMapper->mapping($tour, $tourUpdateRequest);
        $this->assertEquals(null, $result->getId());
        $this->assertEquals('Tour Can Tho', $result->getTitle());
        $this->assertEquals(3, $result->getDuration());
        $this->assertEquals(10, $result->getMaxPeople());
        $this->assertEquals(20, $result->getMinAge());
        $this->assertEquals('Good tour', $result->getOverView());
        $this->assertEquals($user, $result->getCreatedUser());
    }
}
