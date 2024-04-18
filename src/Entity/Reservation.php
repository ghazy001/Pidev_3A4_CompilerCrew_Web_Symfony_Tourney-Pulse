<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_stade", type="integer", nullable=false)
     */
    private $idStade;

    /**
     * @var int
     *
     * @ORM\Column(name="id_PremiereEquipe", type="integer", nullable=false)
     */
    private $idPremiereequipe;

    /**
     * @var int
     *
     * @ORM\Column(name="id_DeuxiemeEquipe", type="integer", nullable=false)
     */
    private $idDeuxiemeequipe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="id_organisateur", type="integer", nullable=false)
     */
    private $idOrganisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStade(): ?int
    {
        return $this->idStade;
    }

    public function setIdStade(int $idStade): self
    {
        $this->idStade = $idStade;

        return $this;
    }

    public function getIdPremiereequipe(): ?int
    {
        return $this->idPremiereequipe;
    }

    public function setIdPremiereequipe(int $idPremiereequipe): self
    {
        $this->idPremiereequipe = $idPremiereequipe;

        return $this;
    }

    public function getIdDeuxiemeequipe(): ?int
    {
        return $this->idDeuxiemeequipe;
    }

    public function setIdDeuxiemeequipe(int $idDeuxiemeequipe): self
    {
        $this->idDeuxiemeequipe = $idDeuxiemeequipe;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdOrganisateur(): ?int
    {
        return $this->idOrganisateur;
    }

    public function setIdOrganisateur(int $idOrganisateur): self
    {
        $this->idOrganisateur = $idOrganisateur;

        return $this;
    }


}
