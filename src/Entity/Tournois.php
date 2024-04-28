<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\TournoisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:TournoisRepository::class)]
class Tournois
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idTournois = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:"Tournament Name Required")]
    #[Assert\Length(min:3,minMessage:"Tournament Name needs at least 3 caracters")]
    private ?string $nomTournois = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:"Tournament Name Required")]
    #[Assert\Length(min:3,minMessage:"Stadium Name needs at least 3 caracters")]
    private ?string $stade = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Mach Number Required")]
    private ?int $nombreMatch = null;

    #[ORM\Column]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column]
    private ?\DateTime $dateFin = null;

    public function getIdTournois(): ?int
    {
        return $this->idTournois;
    }

    public function getNomTournois(): ?string
    {
        return $this->nomTournois;
    }

    public function setNomTournois(string $nomTournois): static
    {
        $this->nomTournois = $nomTournois;

        return $this;
    }

    public function getStade(): ?string
    {
        return $this->stade;
    }

    public function setStade(string $stade): static
    {
        $this->stade = $stade;

        return $this;
    }

    public function getNombreMatch(): ?int
    {
        return $this->nombreMatch;
    }

    public function setNombreMatch(int $nombreMatch): static
    {
        $this->nombreMatch = $nombreMatch;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }


}