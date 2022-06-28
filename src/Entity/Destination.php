<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
class Destination extends AbstractEntity
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

    #[ORM\OneToMany(mappedBy: 'destination', targetEntity: TourPlan::class)]
    private $tourPlans;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tourPlans = new ArrayCollection();
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
     * @return Collection<int, TourPlan>
     */
    public function getTourPlans(): Collection
    {
        return $this->tourPlans;
    }

    public function addTourPlan(TourPlan $tourPlan): self
    {
        if (!$this->tourPlans->contains($tourPlan)) {
            $this->tourPlans[] = $tourPlan;
            $tourPlan->setDestination($this);
        }

        return $this;
    }

    public function removeTourPlan(TourPlan $tourPlan): self
    {
        if ($this->tourPlans->removeElement($tourPlan)) {
            if ($tourPlan->getDestination() === $this) {
                $tourPlan->setDestination(null);
            }
        }

        return $this;
    }
}
