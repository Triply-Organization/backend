<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ListTourRequest extends BaseRequest
{
    public const DEFAULT_LIMIT = 8;
    public const ORDER_TYPE_LIST = ['createdAt', 'price'];
    public const ORDER_BY_LIST = ['asc', 'desc'];
    public const DEFAULT_ORDER_TYPE = 'createdAt';
    public const DEFAULT_ORDER_BY = 'desc';
    public const DEFAULT_OFFSET = 0;
    public const MIN_GUESTS = 1;

    #[Assert\Type('numeric')]
    private $limit = self::DEFAULT_LIMIT;

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

    #[Assert\Type('numeric')]
    private $destination;

    #[Assert\Type('numeric')]
    private $guests = self::MIN_GUESTS;

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
     * @return int
     */
    public function getGuests(): int
    {
        return $this->guests;
    }

    /**
     * @param int $guests
     */
    public function setGuests(int $guests): void
    {
        $this->guests = $guests;
    }
}