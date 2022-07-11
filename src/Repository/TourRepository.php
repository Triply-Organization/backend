<?php

namespace App\Repository;

use App\Entity\Destination;
use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\TicketType;
use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Entity\TourService;
use App\Request\ListTourRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    public const PRICE_LIST_ALIAS = 'pl';
    public const TOUR_SERVICE_ALIAS = 'ts';
    public const PAGE_SIZE = 6;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class, static::TOUR_ALIAS);
    }


    public function getAll(ListTourRequest $listTourRequest): array
    {
        $query = $this->queryTours($listTourRequest);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalTours = count($paginator);
        $pageCount = ceil($totalTours / self::PAGE_SIZE);
        $paginator
            ->getQuery()
            ->setFirstResult(self::PAGE_SIZE * ($listTourRequest->getPage() - 1))
            ->setMaxResults(self::PAGE_SIZE);
        $toursList = [];
        foreach ($paginator as $pageItem) {
            $toursList[] = $pageItem;
        }
        return [
            'tours' => $toursList,
            'totalPages' => $pageCount,
            'page' => $listTourRequest->getPage(),
            'totalTours' => $totalTours
        ];
    }

    public function getAllTourAdmin(ListTourRequest $listTourRequest): array
    {
        $query = $this->queryAdminTours($listTourRequest);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalTours = count($paginator);
        $pageCount = ceil($totalTours / self::PAGE_SIZE);
        $paginator
            ->getQuery()
            ->setFirstResult(self::PAGE_SIZE * ($listTourRequest->getPage() - 1))
            ->setMaxResults(self::PAGE_SIZE);
        $toursList = [];
        foreach ($paginator as $pageItem) {
            $toursList[] = $pageItem;
        }
        return [
            'tours' => $toursList,
            'totalPages' => $pageCount,
            'page' => $listTourRequest->getPage(),
            'totalTours' => $totalTours
        ];
    }

    public function queryTours(ListTourRequest $listTourRequest): QueryBuilder
    {
        $query = $this->createQueryBuilder(static::TOUR_ALIAS);
        $query = $this->join($query);
        $query = $this->filter($query, self::DESTINATION_ALIAS, 'name', $listTourRequest->getDestination());
        $query = $this->moreFilter($query, self::SERVICE_ALIAS, 'id', $listTourRequest->getService());
        $query = $this->moreFilter($query, self::SCHEDULE_ALIAS, 'startDate', $listTourRequest->getStartDate());
        $guests = $listTourRequest->getGuests();
        if (!empty($guests)) {
            foreach ($guests as $guest) {
                $query = $this->moreFilter($query, self::TICKET_TYPE_ALIAS, 'id', $guest);
            }
        }
        $query = $this->andBetween($query, self::PRICE_LIST_ALIAS, 'price', $listTourRequest->getStartPrice(), $listTourRequest->getEndPrice());
        $query = $this->andCustomFilter($query, self::TOUR_ALIAS, 'status', '=', 'enable');
        $query = $this->andIsNull($query, self::TOUR_ALIAS, 'deletedAt');
        $query = $this->sortBy($query, self::PRICE_LIST_ALIAS, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());

        return $query->groupBy('t.id');
    }


    public function queryAdminTours(ListTourRequest $listTourRequest): QueryBuilder
    {
        $query = $this->createQueryBuilder(static::TOUR_ALIAS);
        $query = $this->andIsNull($query, self::TOUR_ALIAS, 'deletedAt');

        return $this->sortBy($query, self::TOUR_ALIAS, 'id', $listTourRequest->getOrderBy());
    }

    public function getTourWithDestination(string $name, int $tourId)
    {
        $query = $this->createQueryBuilder(static::TOUR_ALIAS);
        $query = $this->join($query);
        $query = $this->filter($query, self::DESTINATION_ALIAS, 'name', $name);
        $query = $this->andCustomFilter($query, self::TOUR_ALIAS, 'id', '<>', $tourId);
        $query = $this->andCustomFilter($query, self::TOUR_ALIAS, 'status', '=', 'enable');
        $query = $this->andIsNull($query, self::TOUR_ALIAS, 'deletedAt');

        return $query->getQuery()->getResult();
    }

    public function getPopularTour()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("
                SELECT  t.id , SUM(rd.rate)/count(rd.review) AS rate
                FROM  App\Entity\Tour AS t, App\Entity\ReviewDetail AS rd, App\Entity\Review AS r
                WHERE t.id = r.tour AND r.id = rd.review AND t.deletedAt IS NULL AND t.status = 'enable'
                GROUP BY t.id ORDER BY rate DESC");
        $query->setMaxResults(6);
        return $query->getResult();
    }

    private function join(QueryBuilder $query)
    {
        $query->leftJoin(TourPlan::class, static::TOUR_PLAN_ALIAS, 'WITH', 't.id = tp.tour');
        $query->join(Destination::class, static::DESTINATION_ALIAS, 'WITH', 'tp.destination = d.id');
        $query->leftJoin(TourService::class, static::TOUR_SERVICE_ALIAS, 'WITH', 't.id = ts.tour');
        $query->join(Service::class, static::SERVICE_ALIAS, 'WITH', 'ts.service = s.id');
        $query->leftJoin(Schedule::class, static::SCHEDULE_ALIAS, 'WITH', 't.id = sch.tour');
        $query->leftJoin(PriceList::class, static::PRICE_LIST_ALIAS, 'WITH', 'sch.id = pl.schedule');
        $query->join(TicketType::class, static::TICKET_TYPE_ALIAS, 'WITH', 'pl.type = tt.id');

        return $query;
    }
}
