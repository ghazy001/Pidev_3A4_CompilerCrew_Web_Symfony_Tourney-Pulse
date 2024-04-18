<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(string $urlImage): self
    {
        $this->urlImage = $urlImage;

        return $this;
    }


}
