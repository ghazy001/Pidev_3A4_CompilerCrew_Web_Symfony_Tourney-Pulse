<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImagesStadeRepository;


#[ORM\Entity(repositoryClass: ImagesStadeRepository::class)]

/**
 * ImagesStade
 *
 * @ORM\Table(name="images_stade")
 * @ORM\Entity
 */
class ImagesStade
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
     * @var string
     *
     * @ORM\Column(name="url_image", type="string", length=2555, nullable=false)
     */
    private $urlImage;

    /**
     * @ORM\ManyToOne(targetEntity=Stade::class, inversedBy="images")
     * @ORM\JoinColumn(name="id_stade", referencedColumnName="id")
     */
    private $stade;

    public function getStade(): ?Stade
    {
        return $this->stade;
    }

    public function setStade(?Stade $stade): self
    {
        $this->stade = $stade;

        return $this;
    }
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

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(string $urlImage): static
    {
        $this->urlImage = $urlImage;

        return $this;
    }


}
