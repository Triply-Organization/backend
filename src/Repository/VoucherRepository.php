<?php

namespace App\Repository;

use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voucher>
 *
 * @method Voucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voucher[]    findAll()
 * @method Voucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class, 'v');
    }
}
