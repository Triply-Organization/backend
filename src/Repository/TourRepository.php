<?php

namespace App\Repository;

use App\Entity\Destination;
use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\Ticket;
use App\Entity\TicketType;
use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Entity\TourService;
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
    public const PRICE_LIST_ALIAS = 'pl';
    public const TOUR_SERVICE_ALIAS = 'ts';
    public DestinationRepository $destinationRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class, static::TOUR_ALIAS);
    }

    public function getAll(ListTourRequest $listTourRequest): array
    {
        $tours = $this->createQueryBuilder(static::TOUR_ALIAS);
        $tours = $this->join($tours);
        $tours = $this->sortBy($tours, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());
        $tours = $this->filter($tours, self::DESTINATION_ALIAS, 'id', $listTourRequest->getDestination());
        $tours = $this->moreFilter($tours, self::SERVICE_ALIAS, 'id', $listTourRequest->getService());
        $tours = $this->moreFilter($tours, self::TICKET_TYPE_ALIAS, 'id', $listTourRequest->getGuests());
        $tours = $this->andCustomFilter($tours, self::SCHEDULE_ALIAS, 'startDate', $listTourRequest->getStartDate());
        $tours = $tours->setFirstResult(($listTourRequest->getLimit() - 1) * $listTourRequest->getOffset());
        $tours = $tours->setMaxResults($listTourRequest->getOffset());

        return $tours->getQuery()->getResult();
    }

    public function pagination(ListTourRequest $listTourRequest): array
    {
        return [
            'page' => $listTourRequest->getPage(),
            'offset' => $listTourRequest->getOffset(),
            'total' => count($this->getAll($listTourRequest))
        ];
    }

    public function join($query)
    {
        $query->join(TourPlan::class, static::TOUR_PLAN_ALIAS, 'WITH', 't.id = tp.tour');
        $query->join(Destination::class, static::DESTINATION_ALIAS, 'WITH', 'tp.destination = d.id');
        $query->join(Schedule::class, static::SCHEDULE_ALIAS, 'WITH', 't.id = sch.tour');
        $query->join(PriceList::class, static::PRICE_LIST_ALIAS, 'WITH', 'sch.id = pl.id');
        $query->join(TicketType::class, static::TICKET_TYPE_ALIAS, 'WITH', 'pl.type = tt.id');
        $query->join(TourService::class, static::TOUR_SERVICE_ALIAS, 'WITH', 't.id = ts.tour');
        $query->join(Service::class, static::SERVICE_ALIAS, 'WITH', 'ts.service = s.id');

        return $query;
    }


}
