<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bill>
 *
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    public function statisticalTotalRevenue($year)
    {
        $dateStart = $year . '-01-01 00:00:00';
        $dateEnd = $year . '-12-31 00:00:00';
        $dateStartStatistical = \DateTimeImmutable::createFromFormat('Y-m-d 00:00:00', $dateStart);
        $dateEndStatistical = \DateTimeImmutable::createFromFormat('Y-m-d 00:00:00', $dateEnd);
        $bills = $this->selectBillByDateStart($dateStartStatistical, $dateEndStatistical);
        for ($i = 1; $i <= 12; $i++) {
            $result[$i]['revenue'] = 0;
            $result[$i]['commission'] = 0;
        }
        foreach ($bills as $bill) {
            $time = $bill->getCreatedAt();
            $month = (int)$time->format('m');
            $result[$month]['revenue'] = $result[$month]['revenue'] + $bill->getTotalPrice();
            $result[$month]['commission'] = $result[$month]['commission'] + $bill->getTotalPrice() * 10 / 100;
        }
        return $result;
    }

    public function statisticalBooking($year): array
    {
        $dateStart = $year . '-01-01 00:00:00';
        $dateEnd = $year . '-12-31 00:00:00';
        $dateStartStatistical = \DateTimeImmutable::createFromFormat('Y-m-d 00:00:00', $dateStart);
        $dateEndStatistical = \DateTimeImmutable::createFromFormat('Y-m-d 00:00:00', $dateEnd);
        $bills = $this->selectBillByDateStart($dateStartStatistical, $dateEndStatistical);
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = 0;
        }
        foreach ($bills as $bill) {
            $time = $bill->getCreatedAt();
            $month = (int)$time->format('m');
            $result[$month] = $result[$month] + 1;
        }
        return $result;
    }

    public function selectBillByDateStart(\DateTimeImmutable $dateStart, \DateTimeImmutable $dateEnd)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b FROM App\Entity\Bill b 
            WHERE b.createdAt >= :dateStart AND b.createdAt <= :dateEnd'
        )->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd);
        return $query->getResult();
    }
}
