<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ListTourRequest extends BaseRequest
{
    public const DEFAULT_LIMIT = 6;
    public const ORDER_TYPE_LIST = ['createdAt', 'price'];
    public const ORDER_BY_LIST = ['asc', 'desc'];
    public const DEFAULT_ORDER_TYPE = 'createdAt';
    public const DEFAULT_ORDER_BY = 'desc';
    public const DEFAULT_OFFSET = 1;
    public const DEFAULT_PAGE = 1;

    #[Assert\Type('numeric')]
    private $limit = self::DEFAULT_LIMIT;

    #[Assert\Type('numeric')]
    private $offset = self::DEFAULT_OFFSET;

    #[Assert\Type('numeric')]
    private $page = self::DEFAULT_PAGE;

    #[Assert\Type('numeric')]
    private $startPrice;

    #[Assert\Type('numeric')]
    private $endPrice;

    #[Assert\Choice(
        choices: self::ORDER_TYPE_LIST,
    )]
    #[Assert\Type('string')]
    private $orderType = self::DEFAULT_ORDER_TYPE;

    #[Assert\Choice(
        choices: self::ORDER_BY_LIST,
    )]
    #[Assert\Type('string')]
    private $orderBy = self::DEFAULT_ORDER_BY;

    #[Assert\Type('string')]
    private $destination;

    #[Assert\Type('array')]
    private $guests;

    #[Assert\Type('numeric')]
    private $service;

    #[Assert\Date]
    private $startDate;

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * @param mixed $guests
     */
    public function setGuests($guests): void
    {
        $this->guests = $guests;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
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
     * @return string
     */
    public function getOrderType(): string
    {
        return $this->orderType;
    }

    /**
     * @param string $orderType
     */
    public function setOrderType(string $orderType): void
    {
        $this->orderType = $orderType;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getStartPrice()
    {
        return $this->startPrice;
    }

    /**
     * @param mixed $startPrice
     */
    public function setStartPrice($startPrice): void
    {
        $this->startPrice = $startPrice;
    }


    public function getEndPrice()
    {
        return $this->endPrice;
    }


    public function setEndPrice($endPrice): void
    {
        $this->endPrice = $endPrice;
    }
}
