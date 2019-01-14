<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    const STATUSES = ['processing', 'shipped'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="order",
     *     orphanRemoval=true, cascade={"persist"})
     */
    private $products;

     /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModifiedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AddressOfClient")
     * @ORM\JoinColumn(name="shipping_address_id", referencedColumnName="id", nullable=false)
     */
    private $shippingAddress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AddressOfClient")
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=false)
     */
    private $billingAddress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ShippingMethod")
     * @ORM\JoinColumn(name="shipping_method_id", referencedColumnName="id", nullable=false)
     */
    private $shippingMethod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingNumber;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction",
     *    mappedBy="ordersTransaction", cascade={"persist", "remove"})
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->dateCreatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrderProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrder($this);
        }

        return $this;
    }

    public function removeProduct(OrderProduct $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getOrderOfProduct() === $this) {
                $product->setOrderOfProduct(null);
            }
        }

        return $this;
    }

    public function createOrder(CartOfStore $cartOfStore)
    {
        foreach($cartOfStore->getProducts() as $product){
            $this->addProduct(new OrderProduct($product));
        }
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

    public function getDateModifiedAt(): ?\DateTimeInterface
    {
        return $this->dateModifiedAt;
    }

    public function setDateModifiedAt(\DateTimeInterface $dateModifiedAt): self
    {
        $this->dateModifiedAt = $dateModifiedAt;

        return $this;
    }

    public function getShippingAddress(): ?AddressOfClient
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?AddressOfClient $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getBillingAddress(): ?AddressOfClient
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(AddressOfClient $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::STATUSES)){
            throw new \InvalidArgumentException('Invalid order status');
        }
        $this->status = $status;

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        if ($this!==$transaction->getOrdersTransaction()){
            $transaction->setOrdersTransaction($this);
        }
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod($shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

}