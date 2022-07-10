<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Entity\User;
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
    private $tourRepositoryMock;
    private $tourTransformerMock;
    private $securityMock;
    private $userRepositoryMock;
    private $facilityServiceMock;
    private $destinationServiceMock;
    private $ticketTypeServiceMock;

    public function setUp(): void
    {
        $this->tourTransformerMock = $this->getMockBuilder(TourTransformer::class)->disableOriginalConstructor()->getMock();
        $this->securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $this->facilityServiceMock = $this->getMockBuilder(FacilityService::class)->disableOriginalConstructor()->getMock();
        $this->destinationServiceMock = $this->getMockBuilder(DestinationService::class)->disableOriginalConstructor()->getMock();
        $this->ticketTypeServiceMock = $this->getMockBuilder(TicketTypeService::class)->disableOriginalConstructor()->getMock();
        $this->tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();

    }

    public function testGetAll()
    {
        $listTourRequest = new ListTourRequest();
        $this->tourRepositoryMock->expects($this->once())->method('getAllTourAdmin')->willReturn([
            'tours' => [],
            'totalPages' => 0,
            'page' => 1,
            'totalTours' => 0,
        ]);

        $listTourService = new ListTourService($this->tourRepositoryMock, $this->tourTransformerMock, $this->securityMock,
            $this->userRepositoryMock, $this->facilityServiceMock, $this->destinationServiceMock, $this->ticketTypeServiceMock);
        $result = $listTourService->getAll($listTourRequest);

        $this->assertIsArray($result);
    }

    public function testFindAll()
    {
        $tour = new Tour();
        $listTourRequest = new ListTourRequest();
        $this->tourRepositoryMock->expects($this->once())->method('getAll')->willReturn([
            'tours' => [$tour],
            'totalPages' => 0,
            'page' => 1,
            'totalTours' => 0,
        ]);
        $this->destinationServiceMock->expects($this->once())->method('getAllDestination')->willReturn(array());
        $this->facilityServiceMock->expects($this->once())->method('getAllService')->willReturn(array());
        $this->ticketTypeServiceMock->expects($this->once())->method('getTicketType')->willReturn(array());
        $this->facilityServiceMock->expects($this->once())->method('getPopularTour')->willReturn(array());

        $listTourService = new ListTourService($this->tourRepositoryMock, $this->tourTransformerMock, $this->securityMock,
            $this->userRepositoryMock, $this->facilityServiceMock, $this->destinationServiceMock, $this->ticketTypeServiceMock);
        $result = $listTourService->findAll($listTourRequest);
        $this->assertIsArray($result);
    }

    public function testGetTourOfCustomer()
    {
        $tour = new Tour();
        $tourNext = new Tour();
        $tourNext->setDeletedAt(new \DateTimeImmutable());
        $tours = [$tour, $tourNext];
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $this->securityMock->expects($this->once())->method('getUser')->willReturn($userMock);
        $this->userRepositoryMock->expects($this->once())->method('find')->willReturn($userMock);
        $this->tourRepositoryMock->expects($this->once())->method('findBy')->willReturn($tours);
        $listTourService = new ListTourService($this->tourRepositoryMock, $this->tourTransformerMock, $this->securityMock,
            $this->userRepositoryMock, $this->facilityServiceMock, $this->destinationServiceMock, $this->ticketTypeServiceMock);
        $result = $listTourService->getTourOfCustomer();
        $this->assertIsArray($result);
    }
}
