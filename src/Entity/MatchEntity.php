<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\MatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:MatchRepository::class)]
class MatchEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idMatch = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:"Tournament Name Required")]
    #[Assert\Length(min:3,minMessage:"Tournament Name needs at least 3 caracters")]
    private ?string $nomMatch = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 'today',
        maxMessage: "The date cannot be in the past."
    )]
    private ?\DateTime $dateMatch = null;

    #[ORM\Column(length: 150)]
    private ?string $dureeMatch = null;

    #[ORM\ManyToOne(inversedBy: 'match_entity')]
    #[ORM\JoinColumn(name: 'id_equipe2', referencedColumnName: 'id')]
    private ?Equipe $idEquipe2 = null;

    #[ORM\ManyToOne(inversedBy: 'match_entity')]
    #[ORM\JoinColumn(name: 'id_equipe1', referencedColumnName: 'id')]
    private ?Equipe $idEquipe1 = null;



    #[ORM\ManyToOne(inversedBy: 'match_entity')]
    #[ORM\JoinColumn(name: 'id_tournois', referencedColumnName: 'id_tournois')]
    private ?Tournois $idTournois = null;

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function getNomMatch(): ?string
    {
        return $this->nomMatch;
    }

    public function setNomMatch(string $nomMatch): static
    {
        $this->nomMatch = $nomMatch;

        return $this;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(\DateTimeInterface $dateMatch): static
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    public function getDureeMatch(): ?string
    {
        return $this->dureeMatch;
    }

    public function setDureeMatch(string $dureeMatch): static
    {
        $this->dureeMatch = $dureeMatch;

        return $this;
    }

    public function getIdEquipe2(): ?Equipe
    {
        return $this->idEquipe2;
    }

    public function setIdEquipe2(?Equipe $idEquipe2): static
    {
        $this->idEquipe2 = $idEquipe2;

        return $this;
    }

    public function getIdEquipe1(): ?Equipe
    {
        return $this->idEquipe1;
    }

    public function setIdEquipe1(?Equipe $idEquipe1): static
    {
        $this->idEquipe1 = $idEquipe1;

        return $this;
    }

    public function getIdTournois(): ?Tournois
    {
        return $this->idTournois;
    }

    public function setIdTournois(?Tournois $idTournois): static
    {
        $this->idTournois = $idTournois;

        return $this;
    }


}