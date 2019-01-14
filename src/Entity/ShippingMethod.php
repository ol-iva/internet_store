<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShippingMethodRepository")
 * @ORM\Table(name="shipping_methods")
 */
class ShippingMethod
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
    private $nameOfShippingMethod;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $shippingFee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activeShipping = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreatedAt;

    public function __construct()
    {
        $this->dateCreatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameOfShippingMethod(): ?string
    {
        return $this->nameOfShippingMethod;
    }

    public function setNameOfShippingMethod(string $nameOfShippingMethod): self
    {
        $this->nameOfShippingMethod = $nameOfShippingMethod;

        return $this;
    }

    public function getShippingFee()
    {
        return $this->shippingFee;
    }

    public function setShippingFee($shippingFee): self
    {
        $this->shippingFee = $shippingFee;

        return $this;
    }

    public function getActiveShipping(): ?bool
    {
        return $this->activeShipping;
    }

    public function setActiveShipping(bool $activeShipping): self
    {
        $this->activeShipping = $activeShipping;

        return $this;
    }

    public function getDateCreatedAt(): ?\DateTimeInterface
    {
        return $this->dateCreatedAt;
    }

    public function setDateCreatedAt(?\DateTimeInterface $dateCreatedAt): self
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }
}
