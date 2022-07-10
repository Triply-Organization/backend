<?php


namespace App\Repository;

use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\TypeReview;
use App\Request\GetReviewAllRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends BaseRepository
{
    public const REVIEW_ALIAS = 'r';
    public const REVIEW_DETAIL_ALIAS = 'rd';
    public const TYPE_REVIEW_ALIAS = 'tr';
    public const PAGE_SIZE = 6;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class, 'rw');
    }

    public function getAllReviewAdmin(GetReviewAllRequest $getReviewAllRequest): array
    {
        $query = $this->queryAdminTours($getReviewAllRequest);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalReviews = count($paginator);
        $pageCount = ceil($totalReviews / self::PAGE_SIZE);
        $paginator
            ->getQuery()
            ->setFirstResult(self::PAGE_SIZE * ($getReviewAllRequest->getPage() - 1))
            ->setMaxResults(self::PAGE_SIZE);
        $reviewsList = [];
        foreach ($paginator as $pageItem) {
            $reviewsList[] = $pageItem;
        }
        return [
            'reviews' => $reviewsList,
            'totalPages' => $pageCount,
            'page' => $getReviewAllRequest->getPage(),
            'totalReviews' => $totalReviews
        ];
    }

    public function queryAdminTours(GetReviewAllRequest $getReviewAllRequest): QueryBuilder
    {
        $query = $this->createQueryBuilder(static::REVIEW_ALIAS);
        $query = $this->join($query);
        $query = $this->sortBy($query, self::REVIEW_ALIAS, 'createdAt', 'desc');
        $query = $this->andIsNull($query, self::REVIEW_ALIAS, 'deletedAt');

        return $query;
    }

    private function join($query)
    {
        $query->join(ReviewDetail::class, static::REVIEW_DETAIL_ALIAS, 'WITH', 'r.id = rd.review');
        $query->join(TypeReview::class, static::TYPE_REVIEW_ALIAS, 'WITH', 'rd.type = tr.id');

        return $query;
    }
}
