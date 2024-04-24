<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
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
     * @var \Stade
     *
     * @ORM\ManyToOne(targetEntity="Stade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_stade", referencedColumnName="id")
     * })
     */
    private $idStade;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\NotNull(message="La date ne peut pas être vide")
     * @Assert\Expression(
     *     "value >= this.getCurrentDate()",
     *     message="La date doit être  postérieure à la date actuelle"
     * )
     */
    private $date;

    public function getCurrentDate(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_DeuxiemeEquipe", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="L'équipe ne peut pas être vide")
     * @Assert\NotEqualTo(propertyPath="idPremiereequipe", message="Les équipes ne peuvent pas être les mêmes.")
     */
    private $idDeuxiemeequipe;


    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_PremiereEquipe", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="L'équipe ne peut pas être vide")
     */
    private $idPremiereequipe;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisateur", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="L'organisateur ne peut pas être vide")
     */
    private $idOrganisateur;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $qrCodeBase64;

    /**
     * @return mixed
     */
    public function getQrCodeBase64()
    {
        return $this->qrCodeBase64;
    }

    /**
     * @param mixed $qrCodeBase64
     */
    public function setQrCodeBase64($qrCodeBase64): void
    {
        $this->qrCodeBase64 = $qrCodeBase64;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStade(): ?Stade
    {
        return $this->idStade;
    }

    public function setIdStade(?Stade $idStade): self
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


}
