<?php

namespace App\Repository;

use App\Entity\TypeReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeReview>
 *
 * @method TypeReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeReview[]    findAll()
 * @method TypeReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeReviewRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeReview::class);
    }
}
