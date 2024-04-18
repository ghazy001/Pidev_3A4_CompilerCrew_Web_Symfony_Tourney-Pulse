<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="match")
 * @ORM\Entity
 */
class MatchEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_match", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_match", type="string", length=255, nullable=false)
     */
    private $nomMatch;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_match", type="date", nullable=false)
     */
    private $dateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="duree_match", type="string", length=100, nullable=false)
     */
    private $dureeMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_tournois", type="string", length=255, nullable=false)
     */
    private $nomTournois;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe1", type="string", length=255, nullable=false)
     */
    private $nomEquipe1;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe2", type="string", length=255, nullable=false)
     */
    private $nomEquipe2;

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function getNomMatch(): ?string
    {
        return $this->nomMatch;
    }

    public function setNomMatch(string $nomMatch): self
    {
        $this->nomMatch = $nomMatch;

        return $this;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(\DateTimeInterface $dateMatch): self
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    public function getDureeMatch(): ?string
    {
        return $this->dureeMatch;
    }

    public function setDureeMatch(string $dureeMatch): self
    {
        $this->dureeMatch = $dureeMatch;

        return $this;
    }

    public function getNomTournois(): ?string
    {
        return $this->nomTournois;
    }

    public function setNomTournois(string $nomTournois): self
    {
        $this->nomTournois = $nomTournois;

        return $this;
    }

    public function getNomEquipe1(): ?string
    {
        return $this->nomEquipe1;
    }

    public function setNomEquipe1(string $nomEquipe1): self
    {
        $this->nomEquipe1 = $nomEquipe1;

        return $this;
    }

    public function getNomEquipe2(): ?string
    {
        return $this->nomEquipe2;
    }

    public function setNomEquipe2(string $nomEquipe2): self
    {
        $this->nomEquipe2 = $nomEquipe2;

        return $this;
    }


}
