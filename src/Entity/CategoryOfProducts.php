<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryOfProductsRepository")
 */
class CategoryOfProducts
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $productsInCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __construct()
    {
        $this->productsInCategory = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ? $this->name : 'No category';
    }
    /**
     * @return Collection|Product[]
     */
    public function getProductsInCategory(): Collection
    {
        return $this->productsInCategory;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->productsInCategory->contains($product)) {
            $this->productsInCategory[] = $product;
            $product->setCategory($this);
        }
        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if (!$this->productsInCategory->contains($product)) {
            $this->productsInCategory->removeElement($product);
            //set the owing side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }
        return $this;
    }

}