<?php

namespace App\Repository;

use App\Entity\TourService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TourService>
 *
 * @method TourService|null find($id, $lockMode = null, $lockVersion = null)
 * @method TourService|null findOneBy(array $criteria, array $orderBy = null)
 * @method TourService[]    findAll()
 * @method TourService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourServiceRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TourService::class);
    }
}
