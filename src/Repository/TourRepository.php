<?php

namespace App\Repository;

use App\Entity\Tour;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class, static::TOUR_ALIAS);
    }

    public function getAll(ListTourRequest $listTourRequest): array
    {
        $tours = $this->createQueryBuilder(static::TOUR_ALIAS);
        $tours = $this->filterGuests($tours, 'maxPeople', $listTourRequest->getGuests());
        $tours = $this->filterDestination($tours, 'id', $listTourRequest->getDestination());
        $tours = $this->sortBy($tours, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());
        $tours->setMaxResults($listTourRequest->getLimit())->setFirstResult(ListTourRequest::DEFAULT_OFFSET);

        return $tours->getQuery()->getResult();
    }

    protected function filterGuests(QueryBuilder $query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where(static::TOUR_ALIAS . ".$field >= :$field")->setParameter($field, $value);
    }

    protected function filterDestination(QueryBuilder $query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->innerJoin("App\Entity\TourPlan", "p")
            ->innerJoin("App\Entity\Destination", "d")
            ->andWhere(static::TOUR_ALIAS . ".id = p.tour")
            ->andWhere("p" . ".destination = d.id")
            ->andWhere("d" . ".$field = :$field")
            ->setParameter($field, $value);
    }
}
