<?php

namespace App\Entity;

use App\Repository\MarketPlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarketPlaceRepository::class)]
class MarketPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Price = null;

    #[ORM\Column]
    private ?int $Quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $ProdName = null;

    #[ORM\Column(length: 255)]
    private ?string $ProdDescription = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateProd = null;

    #[ORM\Column(type: Types::BLOB)]
    private $Image = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'MarketPlace')]
    private Collection $avis;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getProdName(): ?string
    {
        return $this->ProdName;
    }

    public function setProdName(string $ProdName): static
    {
        $this->ProdName = $ProdName;

        return $this;
    }

    public function getProdDescription(): ?string
    {
        return $this->ProdDescription;
    }

    public function setProdDescription(string $ProdDescription): static
    {
        $this->ProdDescription = $ProdDescription;

        return $this;
    }

    public function getDateProd(): ?\DateTimeInterface
    {
        return $this->DateProd;
    }

    public function setDateProd(\DateTimeInterface $DateProd): static
    {
        $this->DateProd = $DateProd;

        return $this;
    }

    public function getImage()
    {
        return $this->Image;
    }

    public function setImage($Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setMarketPlace($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getMarketPlace() === $this) {
                $avi->setMarketPlace(null);
            }
        }

        return $this;
    }
}
