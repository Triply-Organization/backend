<?php

namespace App\Repository;

use App\Entity\Tour;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
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
        $tours = $this->filter($tours, 'maxPeople', $listTourRequest->getGuests());
        $tours = $this->andFilter($tours, 'id', $listTourRequest->getDestination());
        $tours = $this->sortBy($tours, $listTourRequest->getOrderType(), $listTourRequest->getOrderBy());
        $tours->setMaxResults($listTourRequest->getLimit())->setFirstResult(TourRequest::DEFAULT_OFFSET);

        return $tours->getQuery()->getResult();
    }

}
