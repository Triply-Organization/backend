<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Request\UserGetAllOrderRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends BaseRepository
{
    public const ORDER_ALIAS = 'o';
    public const PAGE_SIZE = 6;
    public const USER_ALIAS = 'u';

    public function __construct(ManagerRegistry $registry,)
    {
        parent::__construct($registry, Order::class);
    }

    public function getAllOrder(UserGetAllOrderRequest $userGetAllOrderRequest, UserInterface $user): array
    {
        $query = $this->queryTours($user);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalOrders = count($paginator);
        $pageCount = ceil($totalOrders / self::PAGE_SIZE);
        $paginator
            ->getQuery()
            ->setFirstResult(self::PAGE_SIZE * ($userGetAllOrderRequest->getPage() - 1))
            ->setMaxResults(self::PAGE_SIZE);
        $toursList = [];
        foreach ($paginator as $pageItem) {
            $toursList[] = $pageItem;
        }
        return [
            'orders' => $toursList,
            'totalPages' => $pageCount,
            'page' => $userGetAllOrderRequest->getPage(),
            'totalOrders' => $totalOrders
        ];
    }

    public function queryTours(UserInterface $user): QueryBuilder
    {
        $query = $this->createQueryBuilder(static::ORDER_ALIAS);
        $query = $this->filter($query, self::ORDER_ALIAS, 'user', $user->getId());
        $query = $this->andIsNull($query, self::ORDER_ALIAS, 'deletedAt');
        $query = $this->sortBy($query, self::ORDER_ALIAS, 'createdAt' , 'DESC');
        $query = $query->groupBy('o.id');

        return $query;
    }
}
