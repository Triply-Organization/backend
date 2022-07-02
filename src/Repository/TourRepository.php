<?php

namespace App\Repository;

use App\Entity\Destination;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\Ticket;
use App\Entity\TicketType;
use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Tour>
 *
 * @method Tour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tour[]    findAll()
 * @method Tour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourRepository extends BaseRepository
{
    public const TOUR_ALIAS = 't';
    public const DESTINATION_ALIAS = 'd';
    public const TOUR_PLAN_ALIAS = 'tp';
    public const SERVICE_ALIAS = 's';
    public const TICKET_TYPE_ALIAS = 'tt';
    public const SCHEDULE_ALIAS = 'sch';
    public const TICKET_ALIAS = 'tk';
    public const TOUR_SERVICE_ALIAS = 'ts';
    public const MAX_GUEST = 50;
    public DestinationRepository $destinationRepository;
    private QueryBuilder $tours;

    public function __construct(ManagerRegistry $registry, DestinationRepository $destinationRepository)
    {
        parent::__construct($registry, Tour::class, static::TOUR_ALIAS);
        $this->destinationRepository = $destinationRepository;
        $this->tours = $this->createQueryBuilder(static::TOUR_ALIAS);
    }

    public function getAll(ListTourRequest $listTourRequest): array
    {
        $this->tours = $this->join($this->tours);
        $this->tours = $this->sortBy($this->tours, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());
        $this->tours = $this->filter($this->tours, self::DESTINATION_ALIAS, 'id', $listTourRequest->getDestination());
        $this->tours = $this->moreFilter($this->tours, self::SERVICE_ALIAS, 'id', $listTourRequest->getService());
        $this->tours = $this->moreFilter($this->tours, self::TICKET_TYPE_ALIAS, 'id', $listTourRequest->getGuests());
        $this->tours = $this->andCustomFilter($this->tours, self::SCHEDULE_ALIAS, 'startDate', $listTourRequest->getStartDate());
        $this->tours->setMaxResults($listTourRequest->getLimit())->setFirstResult(ListTourRequest::DEFAULT_OFFSET);

        return $this->tours->getQuery()->getResult();
    }

    public function pagination(ListTourRequest $listTourRequest): array
    {
        return [
            'page' => $listTourRequest->getPage(),
            'offset' => $listTourRequest->getOffset(),
            'total' => $this->countRecord()
        ];
    }

    public function countRecord()
    {
        $query = $this->tours->getQuery()->getResult();

        return count($query);
    }

    public function join($query)
    {
        $query->join(TourPlan::class, static::TOUR_PLAN_ALIAS, 'WITH', 't.tourPlans = tp.id ');
        $query->join(Destination::class, static::DESTINATION_ALIAS, 'WITH', 'tp.destination = d.id');
        $query->join(Schedule::class, static::SCHEDULE_ALIAS, 'WITH', 't.schedules = sch.id');
        $query->join(Ticket::class, static::TICKET_ALIAS, 'WITH', 'sch.tickets = tk.id');
        $query->join(TicketType::class, static::TICKET_TYPE_ALIAS, 'WITH', 'tk.type = tt.id');
        $query->join(TourService::class, static::TOUR_SERVICE_ALIAS, 'WITH', 'tt.tourService = ts.tours');
        $query->join(Service::class, static::SERVICE_ALIAS, 'WITH', 'ts.services = s.id');

        return $query;
    }


}
