<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameOfProduct;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionOfProduct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoryOfProducts", inversedBy="productsInCategory")
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceOfProduct;

    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeletedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageOfProduct", mappedBy="product",
     *      orphanRemoval=true)
     */
    private $imagesOfProduct;


    public function __construct()
    {
        $this->imagesOfProduct = new ArrayCollection();
        $this->dateCreatedAt = new \DateTime();
        $this->dateDeletedAt = date_create('0000-00-00');
    }

    public function setId(Product $id): self
    {
        $this->id = $id;

        return $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameOfProduct(): ?string
    {
        return $this->nameOfProduct;
    }

    public function setNameOfProduct(?string $nameOfProduct): self
    {
        $this->nameOfProduct = $nameOfProduct;

        return $this;
    }

    public function getDescriptionOfProduct(): ?string
    {
        return $this->descriptionOfProduct;
    }

    public function setDescriptionOfProduct(?string $descriptionOfProduct): self
    {
        $this->descriptionOfProduct = $descriptionOfProduct;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPriceOfProduct(): ?float
    {
        return $this->priceOfProduct;
    }

    public function setPriceOfProduct(?string $priceOfProduct): self
    {
        $this->priceOfProduct = $priceOfProduct;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDateCreatedAt(): ?\DateTimeInterface
    {
        return $this->dateCreatedAt;
    }

    public function setDateCreatedAt(\DateTimeInterface $dateCreatedAt): self
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    public function getDateDeletedAt(): ?\DateTimeInterface
    {
        return $this->dateDeletedAt;
    }

    public function setDateDeletedAt(?\DateTimeInterface $dateDeletedAt): self
    {
        $this->dateDeletedAt = $dateDeletedAt;

        return $this;
    }

    public function hasStock(): ?bool
    {
        return $this->stock > 0;
    }

    public function calcTotalPrice(): float
    {
        $totalPrice = $this->quantity * $this->priceOfProduct;

        return $totalPrice;
    }

    /**
     * @return Collection|ImageOfProduct[]
     */
    public function getImagesOfProduct(): Collection
    {
        return $this->imagesOfProduct;
    }

    public function addImagesOfProduct(ImageOfProduct $imagesOfProduct): self
    {
        if (!$this->imagesOfProduct->contains($imagesOfProduct)) {
            $this->imagesOfProduct[] = $imagesOfProduct;
            $imagesOfProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImagesOfProduct(ImageOfProduct $imagesOfProduct): self
    {
        if ($this->imagesOfProduct->contains($imagesOfProduct)) {
            $this->imagesOfProduct->removeElement($imagesOfProduct);
            // set the owning side to null (unless already changed)
            if ($imagesOfProduct->getProduct() === $this) {
                $imagesOfProduct->setProduct(null);
            }
        }

        return $this;
    }

}