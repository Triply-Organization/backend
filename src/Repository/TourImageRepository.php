<?php

namespace App\Repository;

use App\Entity\TourImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TourImage>
 *
 * @method TourImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TourImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TourImage[]    findAll()
 * @method TourImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourImageRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TourImage::class);
    }
}
