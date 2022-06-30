<?php

namespace App\Repository;

use App\Entity\Tour;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public const MAX_GUEST = 50;
    public DestinationRepository $destinationRepository;

    public function __construct(ManagerRegistry $registry, DestinationRepository $destinationRepository)
    {
        parent::__construct($registry, Tour::class, static::TOUR_ALIAS);
        $this->destinationRepository = $destinationRepository;
    }

    public function getAll(ListTourRequest $listTourRequest): array
    {
        $tours = $this->createQueryBuilder(static::TOUR_ALIAS);
        if ($listTourRequest->getGuests() !== null && $listTourRequest->getGuests() <= self::MAX_GUEST) {
            $tours = $this->filterGuests($tours, 'maxPeople', $listTourRequest->getGuests());
        }
        $destination = $listTourRequest->getDestination();
        if ($destination !== null && $this->destinationRepository->find($destination) !== null) {
            $tours = $this->filterDestination($tours, 'id', $listTourRequest->getDestination());
        }
        $tours = $this->sortBy($tours, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());
        $tours->setMaxResults($listTourRequest->getLimit())->setFirstResult(ListTourRequest::DEFAULT_OFFSET);

        return $tours->getQuery()->getResult();
    }

    protected function filterGuests($query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where($this->alias . ".$field >= :$field")->setParameter($field, $value);
    }

    protected function filterDestination($query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->innerJoin("App\Entity\TourPlan", "p")
            ->innerJoin("App\Entity\Destination", "d")
            ->andWhere($this->alias . ".id = p.tour")
            ->andWhere("p" . ".destination = d.id")
            ->andWhere("d" . ".$field = :$field")
            ->setParameter($field, $value);
    }
}
