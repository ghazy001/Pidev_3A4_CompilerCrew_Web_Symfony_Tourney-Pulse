<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_equipe1", columns={"id_PremiereEquipe"}), @ORM\Index(name="fk_equipe2", columns={"id_DeuxiemeEquipe"}), @ORM\Index(name="fk_user", columns={"id_organisateur"}), @ORM\Index(name="FK_stade_Reservation", columns={"id_stade"})})
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_DeuxiemeEquipe", referencedColumnName="id")
     * })
     */
    private $idDeuxiemeequipe;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisateur", referencedColumnName="id")
     * })
     */
    private $idOrganisateur;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_PremiereEquipe", referencedColumnName="id")
     * })
     */
    private $idPremiereequipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStade(): ?int
    {
        return $this->idStade;
    }

    public function setIdStade(int $idStade): static
    {
        $this->idStade = $idStade;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdDeuxiemeequipe(): ?Equipe
    {
        return $this->idDeuxiemeequipe;
    }

    public function setIdDeuxiemeequipe(?Equipe $idDeuxiemeequipe): static
    {
        $this->idDeuxiemeequipe = $idDeuxiemeequipe;

        return $this;
    }

    public function getIdOrganisateur(): ?User
    {
        return $this->idOrganisateur;
    }

    public function setIdOrganisateur(?User $idOrganisateur): static
    {
        $this->idOrganisateur = $idOrganisateur;

        return $this;
    }

    public function getIdPremiereequipe(): ?Equipe
    {
        return $this->idPremiereequipe;
    }

    public function setIdPremiereequipe(?Equipe $idPremiereequipe): static
    {
        $this->idPremiereequipe = $idPremiereequipe;

        return $this;
    }
    /*
   *
   *
   * @author : ghazi saoudi
   *
   *
   *
   */


}
