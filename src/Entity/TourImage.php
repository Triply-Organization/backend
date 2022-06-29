<?php

namespace App\Entity;

use App\Repository\TourImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourImageRepository::class)]
class TourImage extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\ManyToOne(targetEntity: Tour::class, inversedBy: 'tourImages')]
    #[ORM\JoinColumn(nullable: false)]
    private $tour;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $image;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }
}
