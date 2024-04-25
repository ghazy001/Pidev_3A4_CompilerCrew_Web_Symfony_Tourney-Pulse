<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateAvis = null;

    #[ORM\Column]
    private ?int $Note = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?MarketPlace $MarketPlace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->DateAvis;
    }

    public function setDateAvis(\DateTimeInterface $DateAvis): static
    {
        $this->DateAvis = $DateAvis;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->Note;
    }

    public function setNote(int $Note): static
    {
        $this->Note = $Note;

        return $this;
    }

    public function getMarketPlace(): ?MarketPlace
    {
        return $this->MarketPlace;
    }

    public function setMarketPlace(?MarketPlace $MarketPlace): static
    {
        $this->MarketPlace = $MarketPlace;

        return $this;
    }
}
