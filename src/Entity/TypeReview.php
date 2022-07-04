<?php

namespace App\Entity;

use App\Repository\TypeReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeReviewRepository::class)]
class TypeReview extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $deletedAt;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: ReviewDetail::class)]
    private $reviewDetails;

    public function __construct()
    {
        $this->reviewDetails = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $reviewDetail->setType($this);
        }

        return $this;
    }

    public function removeReviewDetail(ReviewDetail $reviewDetail): self
    {
        if ($this->reviewDetails->removeElement($reviewDetail)) {
            // set the owning side to null (unless already changed)
            if ($reviewDetail->getType() === $this) {
                $reviewDetail->setType(null);
            }
        }

        return $this;
    }
}
