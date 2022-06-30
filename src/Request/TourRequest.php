<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class TourRequest extends BaseRequest
{
    public const DEFAULT_LIMIT = 8;
    public const ORDER_TYPE_LIST = ['createdAt', 'price'];
    public const ORDER_BY_LIST = ['asc', 'desc'];
    public const DEFAULT_ORDER_TYPE = 'createdAt';
    public const DEFAULT_ORDER_BY = 'desc';
    public const DEFAULT_OFFSET = 0;
    public const MIN_GUESTS = 1;

    #[Assert\Type('integer')]
    private int $limit = self::DEFAULT_LIMIT;


    #[Assert\Choice(
        choices: self::ORDER_TYPE_LIST,
    )]
    private string $orderType = self::DEFAULT_ORDER_TYPE;

    #[Assert\Choice(
        choices: self::ORDER_BY_LIST,
    )]
    private string $orderBy = self::DEFAULT_ORDER_BY;

    #[Assert\Type('int')]
    private int $sevices;

    #[Assert\Type('integer')]
    private $destination;

    #[Assert\Type('integer')]
    private int $guests = self::MIN_GUESTS;

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
        $this->destination = is_numeric($destination) ? (int)$destination : $destination;
    }

    /**
     * @return int
     */
    public function getSevices(): int
    {
        return $this->sevices;
    }

    /**
     * @param int $sevices
     */
    public function setSevices(int $sevices): void
    {
        $this->sevices = $sevices;
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
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = is_numeric($duration) ? (int)$duration : null;
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
}
