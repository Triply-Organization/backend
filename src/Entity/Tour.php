<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour extends AbstractEntity
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

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $deletedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private $createdUser;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: TourImage::class)]
    private $tourImages;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Schedule::class)]
    private $schedules;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: TourService::class)]
    private $tourServices;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Review::class)]
    private $reviews;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tourPlans = new ArrayCollection();
        $this->tourImages = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->tourServices = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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
            if ($tourPlan->getTour() === $this) {
                $tourPlan->setTour(null);
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

    public function getCreatedUser(): ?User
    {
        return $this->createdUser;
    }

    public function setCreatedUser(?User $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    /**
     * @return Collection<int, TourImage>
     */
    public function getTourImages(): Collection
    {
        return $this->tourImages;
    }

    public function addTourImage(TourImage $tourImage): self
    {
        if (!$this->tourImages->contains($tourImage)) {
            $this->tourImages[] = $tourImage;
            $tourImage->setTour($this);
        }

        return $this;
    }

    public function removeTourImage(TourImage $tourImage): self
    {
        if ($this->tourImages->removeElement($tourImage)) {
            if ($tourImage->getTour() === $this) {
                $tourImage->setTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setTour($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getTour() === $this) {
                $schedule->setTour(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param ArrayCollection $tourServices
     */
    public function setTourServices(ArrayCollection $tourServices): void
    {
        $this->tourServices = $tourServices;
    }

    /**
     * @return Collection<int, TourService>
     */
    public function getTourServices(): Collection
    {
        return $this->tourServices;
    }

    public function addTourService(TourService $tourService): self
    {
        if (!$this->tourServices->contains($tourService)) {
            $this->tourServices[] = $tourService;
            $tourService->setTour($this);
        }

        return $this;
    }

    public function removeTourService(TourService $tourService): self
    {
        if ($this->tourServices->removeElement($tourService)) {
            // set the owning side to null (unless already changed)
            if ($tourService->getTour() === $this) {
                $tourService->setTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setTour($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getTour() === $this) {
                $review->setTour(null);
            }
        }

        return $this;
    }
}
