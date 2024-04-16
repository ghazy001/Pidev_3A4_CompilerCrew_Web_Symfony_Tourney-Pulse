<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



/**
 * Stade
 *
 * @ORM\Table(name="stade")
 * @ORM\Entity
 */
class Stade
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;

    
    

    /**
     * @ORM\Column(name="Nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="The name cannot be blank.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "The name cannot be shorter than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+(?:\s+[a-zA-Z]+)*$/",
     *     message="The name must contain only alphabetic characters."
     * )
     */
    private $nom;
    


/**
 * @ORM\Column(name="Lieu", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="The location cannot be blank.")
 * @Assert\Length(
 *      min = 3,
 *      minMessage = "The name cannot be shorter than {{ limit }} characters."
 * )
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z\s]+$/",
 *     message="The location must contain only letters."
 * )
 */
private $lieu;

   /**
 * @ORM\Column(name="Capacity", type="integer", nullable=false)
 * @Assert\NotBlank(message="The capacity cannot be blank.")
 * @Assert\Type(type="integer", message="The capacity must be an integer.")
 * @Assert\GreaterThanOrEqual(value=0, message="The capacity must be a positive number.")
 */
private $capacity;

    /**
 * @ORM\Column(name="Numero", type="integer", nullable=false)
 * @Assert\NotBlank(message="The number cannot be blank.")
 * @Assert\Regex(
 *     pattern="/^\d+$/",
 *     message="The number must contain only digits."
 * )
 */
private $numero;


 /**
     * @ORM\OneToMany(targetEntity=ImagesStade::class, mappedBy="stade", cascade={"persist", "remove"}, orphanRemoval=true)
     */
private $images;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImagesStade $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setStade($this);
        }

        return $this;
    }

    public function removeImage(ImagesStade $image): self
    {
        if ($this->images->removeElement($image)) {
            // Set the owning side to null
            if ($image->getStade() === $this) {
                $image->setStade(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): ?self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * String representation of this class.
     *
     * @return string
     */
    public function __toString(): string
    {
        // Return the team name (nom) when the object is treated as a string
        return $this->nom;
    }
}
