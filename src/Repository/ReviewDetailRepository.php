<?php

namespace App\Repository;

use App\Entity\ReviewDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewDetail>
 *
 * @method ReviewDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewDetail[]    findAll()
 * @method ReviewDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewDetailRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewDetail::class);
    }
}
