<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $comment;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: ReviewDetail::class)]
    private $reviewDetails;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $deletedAt;

    #[ORM\ManyToOne(targetEntity: Tour::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private $tour;

    #[ORM\OneToOne(inversedBy: 'review', targetEntity: Order::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $orderDetail;

    public function __construct()
    {
        $this->reviewDetails = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ReviewDetail>
     */
    public function getReviewDetails(): Collection
    {
        return $this->reviewDetails;
    }

    public function addReviewDetail(ReviewDetail $reviewDetail): self
    {
        if (!$this->reviewDetails->contains($reviewDetail)) {
            $this->reviewDetails[] = $reviewDetail;
            $reviewDetail->setReview($this);
        }

        return $this;
    }

    public function removeReviewDetail(ReviewDetail $reviewDetail): self
    {
        if ($this->reviewDetails->removeElement($reviewDetail)) {
            // set the owning side to null (unless already changed)
            if ($reviewDetail->getReview() === $this) {
                $reviewDetail->setReview(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getOrderDetail(): ?Order
    {
        return $this->orderDetail;
    }

    public function setOrderDetail(Order $orderDetail): self
    {
        $this->orderDetail = $orderDetail;

        return $this;
    }
}
