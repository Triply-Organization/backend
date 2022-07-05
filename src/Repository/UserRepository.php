<?php

namespace App\Repository;

use App\Entity\User;
use App\Request\UserRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository implements PasswordUpgraderInterface
{

    private const USER_ALIAS = 'u';
    public const PAGE_SIZE = 6;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function getQuery(UserRequest $userRequest, array $role)
    {
        $query = $this->createQueryBuilder(self::USER_ALIAS);
        $query = $this->filter($query, self::USER_ALIAS, 'email', $userRequest->getEmail());
       // $query = $this->moreFilter($query, self::USER_ALIAS, 'roles', '"role": "ROLE_CUSTOMER"');

        return $query;
    }

    public function getAll(UserRequest $userRequest, array $role): array
    {
        $query = $this->getQuery($userRequest, $role);


        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalUsers = count($paginator);
        $pageCount = ceil($totalUsers / self::PAGE_SIZE);

        $paginator
            ->getQuery()
            ->setFirstResult(self::PAGE_SIZE * ($userRequest->getPage() - 1))
            ->setMaxResults(self::PAGE_SIZE);
        $usersList = [];
        foreach ($paginator as $pageItem) {
            $usersList[] = $pageItem;
        }

        return [
            'users' => $usersList,
            'totalPages' => $pageCount,
            'page' => $userRequest->getPage(),
            'totalUsers' => $totalUsers
        ];
    }
}
