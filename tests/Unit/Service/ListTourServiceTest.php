<?php

namespace App\Tests\Unit\Service;

use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Request\ListTourRequest;
use App\Service\DestinationService;
use App\Service\FacilityService;
use App\Service\ListTourService;
use App\Service\TicketTypeService;
use App\Transformer\TourTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ListTourServiceTest extends TestCase
{
    public function testGetAll()
    {
        $listTourRequest = new ListTourRequest();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('getAllTourAdmin')->willReturn([
            'tours' => [],
            'totalPages' => 0,
            'page' => 1,
            'totalTours' => 0,
        ]);
        $tourTransformerMock = $this->getMockBuilder(TourTransformer::class)->disableOriginalConstructor()->getMock();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $facilityServiceMock = $this->getMockBuilder(FacilityService::class)->disableOriginalConstructor()->getMock();
        $destinationServiceMock = $this->getMockBuilder(DestinationService::class)->disableOriginalConstructor()->getMock();
        $ticketTypeServiceMock = $this->getMockBuilder(TicketTypeService::class)->disableOriginalConstructor()->getMock();

        $listTourService = new ListTourService($tourRepositoryMock, $tourTransformerMock, $securityMock, $userRepositoryMock,
            $facilityServiceMock, $destinationServiceMock, $ticketTypeServiceMock);
        $result = $listTourService->getAll($listTourRequest);

        $this->assertIsArray($result);
    }
}
