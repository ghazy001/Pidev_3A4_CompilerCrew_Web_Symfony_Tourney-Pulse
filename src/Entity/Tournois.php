<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournois
 *
 * @ORM\Table(name="tournois")
 * @ORM\Entity
 */
class Tournois
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_tournois", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTournois;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_tournois", type="string", length=255, nullable=false)
     */
    private $nomTournois;

    /**
     * @var string
     *
     * @ORM\Column(name="stade", type="string", length=255, nullable=false)
     */
    private $stade;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre_match", type="integer", nullable=false)
     */
    private $nombreMatch;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

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
