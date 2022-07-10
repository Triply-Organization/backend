<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UserGetAllOrderRequest extends BaseRequest
{
    public const DEFAULT_LIMIT = 6;
    public const DEFAULT_OFFSET = 1;
    public const DEFAULT_PAGE = 1;

    #[Assert\Type('numeric')]
    private $limit = self::DEFAULT_LIMIT;

    #[Assert\Type('numeric')]
    private $offset = self::DEFAULT_OFFSET;

    #[Assert\Type('numeric')]
    private $page = self::DEFAULT_PAGE;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

}