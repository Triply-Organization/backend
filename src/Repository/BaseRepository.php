<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected string $entityClass;
    protected string $alias;


    public function __construct(ManagerRegistry $registry, string $entityClass, string $alias = '')
    {
        $this->entityClass = $entityClass;
        $this->alias = $alias;
        parent::__construct($registry, $entityClass);
    }

    public function add(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "UPDATE $this->entityClass $this->alias 
                SET $this->alias.deletedAt = :date 
                WHERE $this->alias.id = $id"
        )->setParameter('date', new DateTimeImmutable());

        return $query->getResult();
    }

    public function deleteWithRelation(string $field, int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "UPDATE $this->entityClass $this->alias 
                SET $this->alias.deletedAt = :date 
                WHERE $this->alias.$field = $id"
        )->setParameter('date', new DateTimeImmutable());

        return $query->getResult();
    }

    public function undoDelete($id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "UPDATE $this->entityClass $this->alias 
                SET $this->alias.deletedAt = NULL
                WHERE $this->alias.id = $id"
        );

        return $query->getResult();
    }

    public function undoDeleteWithRelation(string $field, int $id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "UPDATE $this->entityClass $this->alias 
                SET $this->alias.deletedAt = NULL
                WHERE $this->alias.$field = $id"
        );

        return $query->getResult();
    }

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    protected function sortBy(QueryBuilder $query, string $orderType, string $orderBy): QueryBuilder
    {
        if (empty($orderBy) || empty($orderType)) {
            return $query;
        }

        return $query->orderBy($this->alias . ".$orderType", $orderBy);
    }
}
