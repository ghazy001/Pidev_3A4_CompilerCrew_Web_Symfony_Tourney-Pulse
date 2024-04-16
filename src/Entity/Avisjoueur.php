<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Avisjoueur
 *
 * @ORM\Table(name="avisjoueur", indexes={@ORM\Index(name="idJoueur", columns={"idJoueur"})})
 * @ORM\Entity
 */
class Avisjoueur
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAvis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idavis;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=false)
     */
    private $commentaire;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAvis", type="date", nullable=false)
     */
    private $dateavis;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idJoueur", referencedColumnName="id")
     * })
     */
    private $idjoueur;

    public function getIdavis(): ?int
    {
        return $this->idavis;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateavis(): ?\DateTimeInterface
    {
        return $this->dateavis;
    }

    public function setDateavis(\DateTimeInterface $dateavis): static
    {
        $this->dateavis = $dateavis;

        return $this;
    }

    public function getIdjoueur(): ?User
    {
        return $this->idjoueur;
    }

    public function setIdjoueur(?User $idjoueur): static
    {
        $this->idjoueur = $idjoueur;

        return $this;
    }


}
