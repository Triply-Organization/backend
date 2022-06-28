<?php

namespace App\Repository;

use App\Entity\Tour;
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

    public function getAll(TourRequest $tourRequest): array
    {
        $tours = $this->createQueryBuilder(static::TOUR_ALIAS);
        $tours = $this->filter($tours, 'duration', $tourRequest->getDuration());
        $tours = $this->sortBy($tours, $tourRequest->getOrderType(), $tourRequest->getOrderBy());
        $tours->setMaxResults($tourRequest->getLimit())->setFirstResult(TourRequest::DEFAULT_OFFSET);

        return $tours->getQuery()->getResult();
    }
}
