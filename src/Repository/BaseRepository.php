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

    protected function sortBy(QueryBuilder $query, mixed $alias, string $orderType, string $orderBy): QueryBuilder
    {
        if (empty($orderBy) || empty($orderType)) {
            return $query;
        }

        return $query->orderBy($alias . ".$orderType", $orderBy);
    }

    protected function filter(QueryBuilder $tours, mixed $alias, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $tours;
        }

        return $tours->where($alias . ".$field = :$field")->setParameter($field, $value);
    }

    protected function moreFilter(QueryBuilder $tours, mixed $alias, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $tours;
        }

        return $tours->andWhere($alias . ".$field = :$field")->setParameter($field, $value);
    }

    protected function andCustomFilter(QueryBuilder $tours, mixed $alias, string $field, mixed $expression, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $tours;
        }

        return $tours->andWhere($alias . ".$field" . " $expression " . ":$field")->setParameter($field, $value);
    }

    protected function andBetween(QueryBuilder $tours, mixed $alias, mixed $field, mixed $start, mixed $end): QueryBuilder
    {
        if (empty($start) && empty($end)) {
            return $tours;
        }

        return $tours->andWhere($alias . ".$field BETWEEN " . $start . " AND " . $end);
    }

    protected function andIsNull(QueryBuilder $tours, mixed $alias, string $field): QueryBuilder
    {
        return $tours->andWhere($alias . ".$field IS NULL");
    }

    protected function isLike(QueryBuilder $tours, mixed $alias, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $tours;
        }
        return $tours->andWhere($alias . '.' . $field . ' LIKE ' . '\'%' . $value . '%\'');
    }

}
