<?php

namespace App\Repository;

use App\Entity\TourPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TourPlan>
 *
 * @method TourPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method TourPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method TourPlan[]    findAll()
 * @method TourPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourPlanRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TourPlan::class);
    }

    public function getTourPlans(int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
        SELECT c.name destination, b.day, b.title activities, b.description 
        FROM App\Entity\Tour AS a, App\Entity\TourPlan AS b, App\Entity\Destination AS c 
        WHERE a.id = :id AND a.id = b.tour AND b.destination = c.id'
        )->setParameter('id', $id);

        return $query->execute();
    }
}
