<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TourPlan;
use App\Entity\Tour;
use App\Entity\Destination;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function getServices(int $id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b.id 
            FROM App\Entity\Tour a, App\Entity\TourPlan b, App\Entity\Destination c
            WHERE :id = a.id and a.id = b.id and b.id = c.id '
        )->setParameter('id', $id);
        return $query->execute();
    }
}
