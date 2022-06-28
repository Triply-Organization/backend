<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'integer')]
    private $maxPeople;

    #[ORM\Column(type: 'integer')]
    private $minAge;

    #[ORM\Column(type: 'string', length: 255)]
    private $overView;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: TourPlan::class)]
    private $tourPlans;

    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'tours')]
    private $services;

    #[ORM\OneToOne(mappedBy: 'tour', targetEntity: Price::class, cascade: ['persist', 'remove'])]
    private $price;

    #[ORM\ManyToMany(targetEntity: Destination::class, mappedBy: 'tours')]
    private $destinations;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $deletedAt;

    public function __construct()
    {
        $this->tourPlans = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->destinations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMaxPeople(): ?int
    {
        return $this->maxPeople;
    }

    public function setMaxPeople(int $maxPeople): self
    {
        $this->maxPeople = $maxPeople;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(int $minAge): self
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getOverView(): ?string
    {
        return $this->overView;
    }

    public function setOverView(string $overView): self
    {
        $this->overView = $overView;

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
            $tourPlan->setTour($this);
        }

        return $this;
    }

    public function removeTourPlan(TourPlan $tourPlan): self
    {
        if ($this->tourPlans->removeElement($tourPlan)) {
            // set the owning side to null (unless already changed)
            if ($tourPlan->getTour() === $this) {
                $tourPlan->setTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->addTour($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            $service->removeTour($this);
        }

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): self
    {
        // set the owning side of the relation if necessary
        if ($price->getTour() !== $this) {
            $price->setTour($this);
        }

        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Destination>
     */
    public function getDestinations(): Collection
    {
        return $this->destinations;
    }

    public function addDestination(Destination $destination): self
    {
        if (!$this->destinations->contains($destination)) {
            $this->destinations[] = $destination;
            $destination->addTour($this);
        }

        return $this;
    }

    public function removeDestination(Destination $destination): self
    {
        if ($this->destinations->removeElement($destination)) {
            $destination->removeTour($this);
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
}
