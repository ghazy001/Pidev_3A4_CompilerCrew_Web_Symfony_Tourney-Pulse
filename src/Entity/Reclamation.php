<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReclamationRepository;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_rec", type: "integer", nullable: false)]
    private ?int $idRec;

    #[ORM\Column(name: "date_rec", type: "date", nullable: false)]
    private ?\DateTimeInterface $dateRec;

    #[ORM\Column(name: "object", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le champ 'object' ne peut pas être vide.")]
    private ?string $object;

    #[ORM\Column(name: "reclamation", type: "string", length: 1000, nullable: false)]
    #[Assert\NotBlank(message: "Le champ 'reclamation' ne peut pas être vide.")]
    private ?string $reclamation;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le champ 'mail' ne peut pas être vide.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email;

    #[ORM\Column(name: "etat", type: "string", length: 100)]
    private ?string $etat;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private ?User $id;

    public function getIdRec(): ?int
    {
        return $this->idRec;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->dateRec;
    }

    public function setDateRec(\DateTimeInterface $dateRec): static
    {
        $this->dateRec = $dateRec;
        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;
        return $this;
    }

    public function getReclamation(): ?string
    {
        return $this->reclamation;
    }

    public function setReclamation(string $reclamation): static
    {
        $this->reclamation = $reclamation;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }



    public function getId(): ?User
    {
        return $this->id;
    }

    public function setId(?User $id): static
    {
        $this->id = $id;
        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
