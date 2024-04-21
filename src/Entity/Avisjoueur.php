<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvisjoueurRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvisjoueurRepository::class)]
class Avisjoueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idAvis", type: Types::INTEGER)]
    private ?int $idAvis = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Commentaire is required")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s]*$/",
        message: "Only alphanumeric characters and spaces are allowed"
    )]
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Note is required")]
    private ?float $note = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $dateavis = null;

    #[ORM\ManyToOne(inversedBy: 'avisjoueur')]
    #[ORM\JoinColumn(name: "idJoueur", referencedColumnName: "id")]
    private ?User $user = null;

    public function getIdavis(): ?int
    {
        return $this->idAvis;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

