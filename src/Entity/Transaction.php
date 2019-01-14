<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(name="transactions")
 */
class Transaction
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
    private $methodTransaction;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $totalInTransaction;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Order", inversedBy="transaction",
     *     cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private $ordersTransaction;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    public function __construct(string $methodTransaction, float $totalInTransaction)
    {
        $this->dateCreatedAt = new \DateTime();
        $this->methodTransaction = $methodTransaction;
        $this->totalInTransaction = $totalInTransaction;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethodTransaction(): ?string
    {
        return $this->methodTransaction;
    }

    public function setMethodTransaction(string $methodTransaction): self
    {
        $this->methodTransaction = $methodTransaction;

        return $this;
    }

    public function getTotalInTransaction(): ?float
    {
        return $this->totalInTransaction;
    }

    public function setTotalInTransaction(float $totalInTransaction): self
    {
        $this->totalInTransaction = $totalInTransaction;

        return $this;
    }

    public function getOrdersTransaction(): ?Order
    {
        return $this->ordersTransaction;
    }

    public function setOrdersTransaction(Order $ordersTransaction): self
    {
        $this->ordersTransaction = $ordersTransaction;

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
}