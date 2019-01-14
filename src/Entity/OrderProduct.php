<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository")
 * @ORM\Table(name="order_products")
 */
class OrderProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private $orderOfProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->quantity = $product->getQuantity();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOrderOfProduct(): ?Order
    {
        return $this->orderOfProduct;
    }

    public function setOrderOfProduct(?Order $orderOfProduct): self
    {
        $this->orderOfProduct = $orderOfProduct;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
