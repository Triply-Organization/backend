<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected string $entityClass;
    protected string $alias;

    public function __construct(ManagerRegistry $registry, string $entityClass, $alias = '')
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

    public function remove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    protected function filter(QueryBuilder $query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where($this->alias . ".$field >= :$field")->setParameter($field, $value);
    }

    protected function andFilter(QueryBuilder $query, string $field, mixed $value): QueryBuilder
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

    protected function sortBy(QueryBuilder $query, string $orderType, string $orderBy): QueryBuilder
    {
        if (empty($orderBy) || empty($orderType)) {
            return $query;
        }

        return $query->orderBy($this->alias . ".$orderType", $orderBy);
    }
}
